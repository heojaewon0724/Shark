<?php
// AI 설정: 환경변수 또는 별도 안전한 저장소에서 API 키 로드
// 실제 서비스 시 이 파일은 웹 루트 밖에 두거나 .gitignore 처리 권장
$AI_API_KEY = getenv('AI_API_KEY') ?: '';
// 간단 관리자 플래그 (추후 세션/DB 연동)
$IS_ADMIN = true; // 데모 용: 항상 관리자. 배포 시 세션 값으로 대체

// 간단 토큰/길이 제한 설정
const AI_MAX_INPUT_CHARS = 4000;

function ai_clean_text(string $raw): string {
    $trimmed = mb_substr($raw,0,AI_MAX_INPUT_CHARS);
    // 다중 공백 정리
    return preg_replace('/\s+/u',' ', trim($trimmed));
}

// 매우 간단한 fallback summarizer (문장 빈도 기반)
function fallback_summarize(string $text): array {
    $clean = ai_clean_text($text);
    $sentences = preg_split('/(?<=[\.!?。！？])\s+/u', $clean, -1, PREG_SPLIT_NO_EMPTY);
    $sentences = array_slice($sentences,0,60);
    $freq = [];
    foreach ($sentences as $s) {
        $words = preg_split('/[^a-zA-Z0-9가-힣]+/u', mb_strtolower($s), -1, PREG_SPLIT_NO_EMPTY);
        $seen = [];
        foreach ($words as $w) {
            if(mb_strlen($w) < 2 || mb_strlen($w) > 18) continue;
            if(isset($seen[$w])) continue; // sentence-level uniqueness
            $freq[$w] = ($freq[$w] ?? 0) + 1;
            $seen[$w] = true;
        }
    }
    arsort($freq);
    $scores = [];
    foreach ($sentences as $s) {
        $score = 0; $unique = [];
        $words = preg_split('/[^a-zA-Z0-9가-힣]+/u', mb_strtolower($s), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($words as $w) { if(isset($unique[$w])) continue; $unique[$w]=1; if(isset($freq[$w])) $score += $freq[$w]; }
        $scores[] = ['s'=>$s,'score'=>$score];
    }
    usort($scores, fn($a,$b)=> $b['score'] <=> $a['score']);
    $top = array_slice(array_map(fn($x)=>trim($x['s']), $scores),0,3);
    $keywords = array_slice(array_keys($freq),0,8);
    return [
        'summary' => implode(' ', $top) ?: mb_substr($clean,0,160),
        'keypoints' => array_slice($keywords,0,5),
        'perspective_tags' => ['fallback-generated'],
        'source' => 'fallback'
    ];
}

// --- LLM Summarization (JSON schema style prompt) ---------------------------------
// 실제 OpenAI 등 연동 시 curl 기반 POST 구현. 여기서는 키 존재 시에도
// 외부 호출 코드를 개발자가 채울 수 있게 구조만 제공.
// DB 연동(향후): 요약 결과를 MySQL 테이블 article_summaries(source, hash, summary_json, created_at)
// 에 upsert 하여 재요약 최소화. hash = sha256(raw_trimmed).

function build_summary_prompt(string $raw, string $source, string $title): string {
        $instruction = <<<'PROMPT'
당신은 다국어 뉴스 비교 플랫폼용 요약 보조자입니다.
입력 기사 일부를 바탕으로 구조화된 JSON을 생성하십시오.
규칙:
1. 원문 문장 그대로 복제 금지 (요약/재서술).
2. 확인된 사실과 추정/평가를 구분.
3. 관점 태그는 ['fact-focus','policy-angle','domestic-angle','speculation','human-impact','timeline'] 중 선택(최대 3개).
4. 한국어 출력 (영문 원문이면 한국어 번역 요약).
5. JSON 외 다른 텍스트 금지.
출력 JSON 스키마:
{
    "summary": "3~4문단 핵심 요약",
    "keypoints": ["불릿1","불릿2",... 최대5],
    "perspective_tags": ["tag1","tag2"],
    "timeline": [ {"t":"시점 또는 단계","e":"해당 시점 핵심"} ]
}
PROMPT;
        $trimmed = ai_clean_text($raw);
        return $instruction."\n\n[제목]: {$title}\n[소스]: {$source}\n[본문 일부]:\n".$trimmed;    
}

function call_llm_summary(string $apiKey, string $raw, string $source, string $title): ?array {
        if(!$apiKey) return null; // 키 없으면 패스
        $prompt = build_summary_prompt($raw,$source,$title);
        // TODO: 여기에 실제 LLM API 호출 구현 (예: OpenAI Responses/Chat API)
        // 예시 (개발자 참고, 실제 실행 X):
        /*
        $payload = [
            'model' => 'gpt-4o-mini',
            'response_format' => [ 'type' => 'json_object' ],
            'messages' => [
                ['role'=>'system','content'=>'You are a concise news analysis assistant.'],
                ['role'=>'user','content'=>$prompt]
            ],
            'temperature' => 0.4
        ];
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch,[
            CURLOPT_POST=>true,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_HTTPHEADER=>[
                'Authorization: Bearer '.$apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS=>json_encode($payload,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)
        ]);
        $resp = curl_exec($ch);
        if($resp===false){ curl_close($ch); return null; }
        $status = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if($status>=400) return null;
        $decoded = json_decode($resp,true);
        $content = $decoded['choices'][0]['message']['content'] ?? '';
        $json = json_decode($content,true);
        if(!is_array($json) || !isset($json['summary'])) return null;
        return [
            'summary' => $json['summary'],
            'keypoints' => $json['keypoints'] ?? [],
            'perspective_tags' => $json['perspective_tags'] ?? [],
            'timeline' => $json['timeline'] ?? []
        ];
        */
        return null; // 현재는 미구현 상태로 null 반환 (fallback 유도)
}

function unified_summarize(string $raw, string $source, string $title): array {
        global $AI_API_KEY;
        // 1) LLM 시도
        $llm = call_llm_summary($AI_API_KEY,$raw,$source,$title);
        if($llm){
                return [
                    'summary' => $llm['summary'],
                    'keypoints' => $llm['keypoints'],
                    'perspective_tags' => $llm['perspective_tags'] ?: ['llm-generated'],
                    'timeline' => $llm['timeline'] ?? [],
                    'source' => 'llm'
                ];
        }
        // 2) fallback
        $fb = fallback_summarize($raw);
        $fb['timeline'] = [];
        return $fb;
}

