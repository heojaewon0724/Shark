<?php
// api/ai_summarize.php : 기사 일부(raw)를 받아 요약/키포인트/관점 태그 반환
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
if($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'METHOD_NOT_ALLOWED']); exit; }

require_once __DIR__.'/../ai_config.php';

global $AI_API_KEY, $IS_ADMIN;
if(!$IS_ADMIN){ http_response_code(403); echo json_encode(['error'=>'FORBIDDEN']); exit; }

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody, true);
if(!is_array($payload)) { http_response_code(400); echo json_encode(['error'=>'INVALID_JSON']); exit; }
$raw = trim($payload['raw'] ?? '');
$source = $payload['source'] ?? 'UNKNOWN';
$title = trim($payload['title'] ?? '');
if($raw === '') { http_response_code(400); echo json_encode(['error'=>'NO_CONTENT']); exit; }

$clean = ai_clean_text($raw);

// 향후 MySQL 캐시 로직 예시:
// $hash = hash('sha256',$clean.$source);
// SELECT summary_json FROM article_summaries WHERE hash=? LIMIT 1;
// if(hit) return cached; else 아래 unified_summarize 실행 후 INSERT.

$res = unified_summarize($clean, $source, $title);
$resp = [
  'summary' => $res['summary'],
  'keypoints' => $res['keypoints'],
  'perspective_tags' => $res['perspective_tags'],
  'timeline' => $res['timeline'] ?? [],
  'model' => $res['source'],
  'sourceLabel' => $source,
  'chars_in' => mb_strlen($clean)
];
echo json_encode($resp, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
