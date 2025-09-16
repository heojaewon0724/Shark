<?php
// compare_event.php : 특정 사건에 대한 여러 매체 기사 요약/비교/분석 틀
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>사건 비교 분석 | SH.A.R.K</title>
<link rel="stylesheet" href="common.css" />
<style>
body { scroll-behavior:smooth; }
/* main 상단 padding 140px = 고정 header(84px) + 추가 여백(약 56px) */
main { max-width:1280px; padding:140px 24px 80px; margin:0 auto; }
.breadcrumb { font-size:.8rem; color:var(--color-text-soft); margin-bottom:12px; }
.page-title { margin:0 0 10px; font-size:2rem; letter-spacing:-1px; }
.disclaimer { font-size:.72rem; line-height:1.4; background:var(--color-surface); border:1px solid var(--color-border); padding:10px 14px; border-radius:8px; color:var(--color-text-soft); margin:0 0 28px; }
.section { margin:50px 0 60px; }
.section h2 { font-size:1.35rem; margin:0 0 18px; letter-spacing:-0.5px; }
.sources-grid { display:grid; gap:20px; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); }
.source-card { background:var(--color-surface); border:1px solid var(--color-border); border-radius:14px; padding:16px 18px 18px; box-shadow:var(--shadow-sm); display:flex; flex-direction:column; gap:10px; position:relative; }
.source-card:hover { box-shadow:var(--shadow-md); border-color:var(--color-border-strong); }
.source-card header { display:flex; flex-direction:column; gap:4px; }
.source-badge { display:inline-block; background:var(--color-primary); color:#fff; padding:4px 10px; font-size:.7rem; font-weight:600; letter-spacing:.5px; border-radius:20px; }
.source-meta { font-size:.65rem; color:var(--color-text-soft); }
.source-summary { font-size:.78rem; line-height:1.45; color:var(--color-text-soft); }
.source-keypoints { margin:6px 0 0; padding-left:16px; }
.source-keypoints li { font-size:.72rem; margin:2px 0; }
.edit-btn { position:absolute; top:10px; right:10px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:6px; padding:3px 7px; font-size:.65rem; cursor:pointer; color:var(--color-text-soft); transition:var(--transition); }
.edit-btn:hover { color:var(--color-primary); border-color:var(--color-primary); }
.compare-table-wrapper { overflow:auto; border:1px solid var(--color-border); border-radius:12px; background:var(--color-surface); box-shadow:var(--shadow-sm); }
.compare-table { width:100%; border-collapse:collapse; font-size:.75rem; min-width:760px; }
.compare-table th, .compare-table td { padding:10px 12px; border-bottom:1px solid var(--color-border); text-align:left; vertical-align:top; }
.compare-table th { background:linear-gradient(90deg,#fff,#f3f7fc); font-size:.65rem; letter-spacing:.5px; color:var(--color-text-soft); position:sticky; top:0; z-index:2; }
.compare-table tbody tr:hover td { background:#f8fafc; }
.tag-diff { display:inline-block; background:#fee2e2; color:#b91c1c; font-size:.6rem; padding:3px 6px; border-radius:12px; margin:2px 4px 2px 0; }
.tag-common { display:inline-block; background:#d1fae5; color:#065f46; font-size:.6rem; padding:3px 6px; border-radius:12px; margin:2px 4px 2px 0; }
.ai-panel { background:var(--color-surface); border:1px solid var(--color-border); border-radius:16px; padding:22px 26px 26px; box-shadow:var(--shadow-md); }
.ai-panel h3 { margin:0 0 12px; font-size:1.05rem; letter-spacing:-0.5px; }
.ai-panel p { font-size:.82rem; line-height:1.5; margin:0 0 12px; color:var(--color-text-soft); }
.inline-code { font-family:ui-monospace,monospace; font-size:.7rem; background:#f1f5f9; padding:2px 5px; border-radius:4px; }
.discussion-box { background:var(--color-surface); border:1px solid var(--color-border); padding:18px 18px 22px; border-radius:14px; box-shadow:var(--shadow-sm); }
.discussion-box h3 { margin:0 0 10px; font-size:1.05rem; }
.input-group { display:flex; flex-direction:column; gap:6px; margin:0 0 12px; }
.input-group label { font-size:.68rem; font-weight:600; color:var(--color-text-soft); letter-spacing:.4px; }
.input-group textarea { resize:vertical; min-height:90px; padding:10px 12px; font-family:inherit; border:1px solid var(--color-border); border-radius:8px; font-size:.75rem; line-height:1.4; }
.input-group textarea:focus { outline:none; border-color:var(--color-primary); box-shadow:var(--shadow-focus); }
.primary-btn { background:var(--color-primary); color:#fff; border:1px solid var(--color-primary); padding:10px 18px; border-radius:8px; cursor:pointer; font-size:.8rem; font-weight:600; letter-spacing:.4px; transition:var(--transition); }
.primary-btn:hover { background:var(--color-primary-hover); }
.primary-btn:active { transform:scale(.97); }
.editable { outline:1px dashed transparent; transition:var(--transition); }
.editable:focus { outline:1px dashed var(--color-primary); background:#fff; }
.notice-bar { background:#fffbe6; border:1px solid #facc15; color:#92400e; padding:8px 12px; font-size:.65rem; border-radius:8px; margin:-4px 0 20px; }
.keywords-box { display:flex; flex-wrap:wrap; gap:4px; }
.keyword-chip { background:#e0f2fe; color:#0369a1; font-size:.6rem; padding:4px 8px; border-radius:12px; }
footer { margin:80px 0 0; font-size:.65rem; text-align:center; color:var(--color-text-soft); }
@media (max-width:900px){ main{padding:120px 18px 60px;} .compare-table{font-size:.7rem;} }
</style>
</head>
<body>
<?php include 'top.html'; ?>
<main style="display:flex;align-items:flex-start;gap:40px;">
  <aside style="position:sticky;top:120px;align-self:flex-start;min-width:180px;max-width:220px;font-size:.78rem;background:var(--color-surface);border:1px solid var(--color-border);padding:14px 16px 18px;border-radius:12px;box-shadow:var(--shadow-sm);height:max-content;">
    <nav style="display:flex;flex-direction:column;gap:8px;">
      <a href="MAIN.php" style="text-decoration:none;color:var(--color-text-soft);font-weight:600;font-size:.75rem;">HOME</a>
      <a href="#sources" style="text-decoration:none;color:var(--color-text-soft);">기사 요약</a>
      <a href="#comparison" style="text-decoration:none;color:var(--color-text-soft);">비교</a>
      <a href="#ai-analysis" style="text-decoration:none;color:var(--color-text-soft);">AI 분석</a>
      <a href="#discussion" style="text-decoration:none;color:var(--color-text-soft);">토론</a>
    </nav>
  </aside>
  <div style="flex:1 1 auto;min-width:0;">
  <nav class="breadcrumb">HOME / 사건 비교 / <strong>찰리 커크 피살 사건</strong></nav>
  <h1 class="page-title">찰리 커크 피살 사건 매체별 비교</h1>
  <p class="disclaimer">이 페이지는 사용자가 제공한 자료를 기반으로 한 <strong>데모/초안</strong>입니다. 실제 사실관계는 각 언론의 원문 기사와 공식 발표를 반드시 교차 검증해야 하며 잘못된 정보나 맥락 상실 가능성이 있습니다. 본 페이지 요약은 <em>원문을 그대로 복제하지 않는 독립적 요약</em>이며, 저작권은 각 매체(BBC, NYT, YTN)에 있습니다. <strong>AI 요약 버튼</strong>은 외부 LLM(또는 현재는 Fallback Heuristic)을 통해 자동 생성되며, 오류나 환각(hallucination)이 포함될 수 있으니 인용 전 교차 확인하십시오.</p>
  <div class="notice-bar">기사 원문 URL을 추가·수정하려면 각 카드의 'EDIT' 버튼을 누르십시오.</div>
  <section id="sources" class="section">
    <h2>1. 매체별 기사 요약 (Draft)</h2>
    <div class="sources-grid" id="sourcesGrid">
      <!-- BBC -->
      <article class="source-card" data-source="BBC">
        <button class="edit-btn" onclick="enableEdit(this)">EDIT</button>
        <span class="source-badge">BBC</span>
        <p class="source-summary editable" contenteditable="false" data-field="summary">BBC Korean은 사건 발생 배경, 초기 보고된 시간대, 관계 당국의 공식 확인 여부 중심으로 간결히 정리하며 추측성 요소는 배제하고 현재까지 확인된 사실(시간·장소·피해 관련 알려진 범위)을 우선적으로 배열한 형태로 보도한 것으로 요약됩니다.</p>
        <ul class="source-keypoints" data-field="keypoints">
          <li class="editable" contenteditable="false">사실 위주 핵심 포인트 1</li>
          <li class="editable" contenteditable="false">당국 발표 관련 포인트 2</li>
          <li class="editable" contenteditable="false">추가 확인 대기 포인트 3</li>
        </ul>
        <div class="input-group">
          <label>원문 URL</label>
          <input type="url" class="url-input" value="https://www.bbc.com/korean/articles/cyv6ql6pmmmo" placeholder="https://" style="padding:8px 10px;font-size:.7rem;border:1px solid var(--color-border);border-radius:6px;width:100%;" />
        </div>
        <div class="input-group">
          <label>본문 일부 붙여넣기 (저작권 주의 / 핵심 단락만)</label>
          <textarea class="article-raw" placeholder="원문 기사 일부(짧게) 붙여넣기"></textarea>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <button type="button" class="primary-btn" style="padding:8px 14px;font-size:.65rem;" onclick="generateLocalSummary(this,'BBC')">로컬 요약</button>
          <button type="button" class="primary-btn" data-ai="1" style="background:#0f766e;border-color:#0f766e;padding:8px 14px;font-size:.65rem;" onclick="requestAISummary(this,'BBC')">AI 요약</button>
          <button type="button" class="primary-btn" style="background:#475569;border-color:#475569;padding:8px 14px;font-size:.65rem;" onclick="resetCard(this)">초기화</button>
        </div>
        <p style="font-size:.55rem;color:var(--color-text-soft);margin:6px 0 0;">※ 전문 저장/배포 금지. 내부 분석 용도.</p>
      </article>
      <!-- NYT -->
      <article class="source-card" data-source="NYT">
        <button class="edit-btn" onclick="enableEdit(this)">EDIT</button>
        <span class="source-badge">NYT</span>
        <p class="source-summary editable" contenteditable="false" data-field="summary">(요약 초안) NYT 특유의 분석적/정책 맥락 강조 톤 가정. 실제 기사 요약을 넣고 재생성 기능을 추후 AI API 로 연동할 수 있습니다.</p>
        <ul class="source-keypoints" data-field="keypoints">
          <li class="editable" contenteditable="false">정책/배경 맥락 포인트 1</li>
          <li class="editable" contenteditable="false">추정/가설 구분 포인트 2</li>
          <li class="editable" contenteditable="false">수치/데이터 관련 포인트 3</li>
        </ul>
        <div class="input-group">
          <label>원문 URL</label>
          <input type="url" class="url-input" placeholder="https://" style="padding:8px 10px;font-size:.7rem;border:1px solid var(--color-border);border-radius:6px;width:100%;" />
        </div>
        <div class="input-group">
          <label>본문 일부 붙여넣기 (선택)</label>
          <textarea class="article-raw" placeholder="NYT 기사 단락 일부 (영문)"></textarea>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <button type="button" class="primary-btn" style="padding:8px 14px;font-size:.65rem;" onclick="generateLocalSummary(this,'NYT')">로컬 요약</button>
          <button type="button" class="primary-btn" data-ai="1" style="background:#0f766e;border-color:#0f766e;padding:8px 14px;font-size:.65rem;" onclick="requestAISummary(this,'NYT')">AI 요약</button>
          <button type="button" class="primary-btn" style="background:#475569;border-color:#475569;padding:8px 14px;font-size:.65rem;" onclick="resetCard(this)">초기화</button>
        </div>
        <p style="font-size:.55rem;color:var(--color-text-soft);margin:6px 0 0;">※ Citation 필요. 전문 저장 금지.</p>
      </article>
      <!-- YTN -->
      <article class="source-card" data-source="YTN">
        <button class="edit-btn" onclick="enableEdit(this)">EDIT</button>
        <span class="source-badge">YTN</span>
        <p class="source-summary editable" contenteditable="false" data-field="summary">YTN은 국내 독자 대상으로 사건의 파급 효과와 추가 수사 방향, 관계자 코멘트(있을 경우) 등을 배치하면서 국내 반응·정책적 함의 가능성에 한 단락을 할애한 형태의 구성을 취한 것으로 요약됩니다. 확인되지 않은 부분은 신중하게 표현되는 편입니다.</p>
        <ul class="source-keypoints" data-field="keypoints">
          <li class="editable" contenteditable="false">국내 시각 핵심 포인트 1</li>
          <li class="editable" contenteditable="false">공식 발표/경찰 라인 포인트 2</li>
          <li class="editable" contenteditable="false">현장/목격 정황 포인트 3</li>
        </ul>
        <div class="input-group">
          <label>원문 URL</label>
          <input type="url" class="url-input" value="https://www.ytn.co.kr/_ln/0134_202509141230516037_001" placeholder="https://" style="padding:8px 10px;font-size:.7rem;border:1px solid var(--color-border);border-radius:6px;width:100%;" />
        </div>
        <div class="input-group">
          <label>본문 일부 붙여넣기 (국문)</label>
          <textarea class="article-raw" placeholder="YTN 기사 단락 일부 (국문)"></textarea>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <button type="button" class="primary-btn" style="padding:8px 14px;font-size:.65rem;" onclick="generateLocalSummary(this,'YTN')">로컬 요약</button>
          <button type="button" class="primary-btn" data-ai="1" style="background:#0f766e;border-color:#0f766e;padding:8px 14px;font-size:.65rem;" onclick="requestAISummary(this,'YTN')">AI 요약</button>
          <button type="button" class="primary-btn" style="background:#475569;border-color:#475569;padding:8px 14px;font-size:.65rem;" onclick="resetCard(this)">초기화</button>
        </div>
        <p style="font-size:.55rem;color:var(--color-text-soft);margin:6px 0 0;">※ 저작권 있는 원문은 짧은 발췌만.</p>
      </article>
    </div>
  </section>
  <section id="comparison" class="section">
    <h2>2. 키워드 & 관점 비교</h2>
    <div style="display:flex; gap:24px; flex-wrap:wrap; align-items:flex-start;">
      <div style="flex:1 1 420px; min-width:300px;">
        <h3 style="margin:0 0 10px;font-size:.95rem;letter-spacing:-.3px;">자동 추출 키워드</h3>
        <div id="keywordsContainer" class="keywords-box"></div>
        <button class="primary-btn" style="margin-top:12px;" onclick="extractKeywords()">KEYWORD 추출</button>
      </div>
      <div style="flex:2 1 520px; min-width:360px;">
        <h3 style="margin:0 0 10px;font-size:.95rem;letter-spacing:-.3px;">교집합 / 차이</h3>
        <div class="compare-table-wrapper">
          <table class="compare-table" id="compareTable">
            <thead>
              <tr>
                <th style="width:110px;">분류</th>
                <th>BBC</th>
                <th>NYT</th>
                <th>YTN</th>
              </tr>
            </thead>
            <tbody id="compareTbody">
              <tr><td>공통 키워드</td><td colspan="3" id="commonKeywords" style="font-size:.7rem;color:var(--color-text-soft);">추출 대기...</td></tr>
              <tr><td>BBC만</td><td id="onlyBBC" colspan="3" style="font-size:.7rem;color:var(--color-text-soft);">-</td></tr>
              <tr><td>NYT만</td><td id="onlyNYT" colspan="3" style="font-size:.7rem;color:var(--color-text-soft);">-</td></tr>
              <tr><td>YTN만</td><td id="onlyYTN" colspan="3" style="font-size:.7rem;color:var(--color-text-soft);">-</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
  <section id="ai-analysis" class="section">
    <h2>3. AI 통합 분석 (Demo)</h2>
    <div class="ai-panel" id="aiPanel">
      <h3>초기 통합 분석</h3>
      <p id="aiAnalysisText">키워드 추출 후 버튼을 눌러 데모 분석을 생성하세요.</p>
      <button class="primary-btn" onclick="generateDemoAnalysis()">DEMO 분석 생성</button>
    </div>
  </section>
  <section id="discussion" class="section">
    <h2>4. 사용자 토론</h2>
    <div class="discussion-box">
      <h3>간단 의견 남기기</h3>
      <div class="input-group">
        <label for="opinion">의견 (임시 저장만)</label>
        <textarea id="opinion" placeholder="당신은 어떤 매체의 관점이 더 설득력 있다고 생각하나요? 왜 그렇죠?"></textarea>
      </div>
      <button class="primary-btn" onclick="saveOpinion()">임시 저장</button>
      <p id="saveStatus" style="font-size:.65rem;color:var(--color-text-soft);margin-top:10px;">저장 전</p>
    </div>
  </section>
  <footer>© 2025 SH.A.R.K Comparative News Demo. 모든 내용은 검증되지 않은 초안일 수 있습니다.</footer>
  </div>
</main>
<script>
function enableEdit(btn){
  const card = btn.closest('.source-card');
  const editableEls = card.querySelectorAll('.editable');
  const editing = btn.dataset.editing === '1';
  if(!editing){
    editableEls.forEach(el=>{ el.contentEditable='true'; el.classList.add('editing'); });
    btn.textContent='SAVE';
    btn.dataset.editing='1';
  } else {
    editableEls.forEach(el=>{ el.contentEditable='false'; el.classList.remove('editing'); });
    btn.textContent='EDIT';
    btn.dataset.editing='0';
  }
}
function tokenize(text){
  return (text||'').toLowerCase().replace(/[^a-zA-Z0-9가-힣\s]/g,' ').split(/\s+/).filter(w=>w.length>1 && w.length<18);
}
function extractKeywords(){
  const summaries=[...document.querySelectorAll('.source-summary')].map(n=>n.textContent);
  const perSource=summaries.map(s=>{
    const freq={};
    tokenize(s).forEach(w=>{freq[w]=(freq[w]||0)+1;});
    return Object.entries(freq).filter(([w,c])=>c>1||w.length>4).sort((a,b)=>b[1]-a[1]).slice(0,12).map(x=>x[0]);
  });
  const sources=['BBC','NYT','YTN'];
  const container=document.getElementById('keywordsContainer');
  container.innerHTML='';
  perSource.forEach((arr,i)=>{
    const frag=document.createElement('div');
    frag.style.margin='4px 8px 6px 0';
    frag.innerHTML='<strong style="font-size:.65rem;color:var(--color-text-soft);">'+sources[i]+'</strong> ' + arr.map(k=>'<span class="keyword-chip">'+k+'</span>').join('');
    container.appendChild(frag);
  });
  const [a,b,c]=perSource.map(Set);
  const inter=[...a].filter(x=>b.has(x)&&c.has(x));
  document.getElementById('commonKeywords').textContent=inter.length?inter.join(', '):'없음';
  document.getElementById('onlyBBC').textContent=[...a].filter(x=>!b.has(x)&&!c.has(x)).join(', ')||'-';
  document.getElementById('onlyNYT').textContent=[...b].filter(x=>!a.has(x)&&!c.has(x)).join(', ')||'-';
  document.getElementById('onlyYTN').textContent=[...c].filter(x=>!a.has(x)&&!b.has(x)).join(', ')||'-';
  window.__lastKeywords={inter,perSource};
}
function generateDemoAnalysis(){
  const panel=document.getElementById('aiAnalysisText');
  if(!window.__lastKeywords){ panel.textContent='먼저 키워드를 추출하세요.'; return; }
  const {inter,perSource}=window.__lastKeywords;
  const focus=inter.slice(0,5).join(', ');
  panel.textContent='세 매체 모두 '+(focus||'공통 핵심어 부족')+' 에 관심을 보입니다. BBC는 객관 기술 비중, NYT는 정책/배경 맥락, YTN은 국내 관점과 현장성 강조 경향을 드러내는 패턴(가정)입니다. 실제 기사 입력 시 보다 정밀한 의미 비교가 가능합니다.';
}
function saveOpinion(){
  const v=document.getElementById('opinion').value.trim();
  const status=document.getElementById('saveStatus');
  if(!v){ status.textContent='내용이 비어 있습니다.'; return; }
  // 로컬세션 저장 (데모)
  try { sessionStorage.setItem('opinion_draft', v); status.textContent='임시 저장 완료 (세션 유지)'; }
  catch(e){ status.textContent='저장 실패: '+e.message; }
}
// --- Local summarization (simple heuristic) ---
function sentenceSplit(txt){
  return (txt||'').replace(/\s+/g,' ').split(/(?<=[.!?。！？])\s+/).filter(s=>s.trim().length>8);
}
function scoreSentence(s, freq){
  const words=s.toLowerCase().replace(/[^a-zA-Z0-9가-힣\s]/g,' ').split(/\s+/);
  let score=0; let uniq=new Set();
  words.forEach(w=>{ if(w.length>1){ uniq.add(w);} });
  uniq.forEach(w=>{ if(freq[w]) score+=freq[w]; });
  return score;
}
function summarize(raw){
  const sentences=sentenceSplit(raw).slice(0,40); // limit
  const freq={};
  sentences.forEach(s=>{
    s.toLowerCase().replace(/[^a-zA-Z0-9가-힣\s]/g,' ').split(/\s+/).forEach(w=>{ if(w.length>1 && w.length<18){ freq[w]=(freq[w]||0)+1; }});
  });
  const scored=sentences.map(s=>({s, score:scoreSentence(s,freq)}));
  scored.sort((a,b)=>b.score-a.score);
  return {
    topSentences: scored.slice(0,3).map(o=>o.s.trim()),
    keywords: Object.entries(freq).filter(([w,c])=>c>1||w.length>4).sort((a,b)=>b[1]-a[1]).slice(0,8).map(x=>x[0])
  };
}
function generateLocalSummary(btn, source){
  const card=btn.closest('.source-card');
  const raw=card.querySelector('.article-raw').value.trim();
  if(!raw){ alert('본문 일부를 먼저 붙여넣으세요.'); return; }
  const {topSentences, keywords}=summarize(raw);
  const summaryEl=card.querySelector('.source-summary');
  summaryEl.textContent= topSentences.join(' ');
  // keypoints 갱신
  const ul=card.querySelector('.source-keypoints');
  ul.innerHTML='';
  keywords.slice(0,5).forEach(k=>{
    const li=document.createElement('li');
    li.textContent=k;
    li.className='editable';
    li.contentEditable='false';
    ul.appendChild(li);
  });
  // highlight flash
  summaryEl.style.transition='background 0.6s';
  summaryEl.style.background='#fff8d1';
  setTimeout(()=>summaryEl.style.background='transparent',700);
}
function resetCard(btn){
  const card=btn.closest('.source-card');
  card.querySelector('.article-raw').value='';
  // do not reset title, only summary & keypoints revert to placeholder notice
  const summaryEl=card.querySelector('.source-summary');
  summaryEl.textContent='(요약 초기화됨) 새 본문을 붙여넣고 요약 생성 버튼을 다시 누르세요.';
  const ul=card.querySelector('.source-keypoints');
  ul.innerHTML='<li class="editable" contenteditable="false">키워드1</li><li class="editable" contenteditable="false">키워드2</li><li class="editable" contenteditable="false">키워드3</li>';
}
async function requestAISummary(btn, source){
  const card=btn.closest('.source-card');
  const raw=card.querySelector('.article-raw').value.trim();
  // 제목 요소가 제거된 경우 source 이름을 기본 사용
  const titleEl = card.querySelector('[data-field="title"]');
  const title = titleEl ? titleEl.textContent.trim() : source + ' 기사';
  if(!raw){ alert('본문 일부를 먼저 붙여넣으세요.'); return; }
  const summaryEl=card.querySelector('.source-summary');
  const ul=card.querySelector('.source-keypoints');
  const originalBtnText=btn.textContent;
  btn.textContent='요약 중...'; btn.disabled=true; summaryEl.style.opacity='.6';
  try {
    const res = await fetch('api/ai_summarize.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({raw, source, title})});
    if(!res.ok) throw new Error('HTTP '+res.status);
    const data = await res.json();
    summaryEl.textContent = data.summary || '(AI 요약 실패)';
    ul.innerHTML='';
    (data.keypoints||[]).forEach(k=>{
      const li=document.createElement('li'); li.textContent=k; li.className='editable'; li.contentEditable='false'; ul.appendChild(li);
    });
    // flash
    summaryEl.style.transition='background 0.6s';
    summaryEl.style.background='#d1fae5';
    setTimeout(()=>summaryEl.style.background='transparent',700);
  } catch(e){
    alert('AI 요약 실패: '+e.message+'\n로컬 요약 기능을 사용하세요.');
  } finally {
    btn.textContent=originalBtnText; btn.disabled=false; summaryEl.style.opacity='1';
  }
}
</script>
</body>
</html>
