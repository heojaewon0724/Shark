<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SH.A.R.K 뉴스 허브</title>
    <link rel="stylesheet" href="common.css" />
    <style>
        h1,h2,h3,h4 { font-weight:600; letter-spacing:-0.5px; color:var(--color-text); }
        h2 { font-size: 1.4rem; margin:0 0 18px; }
        p { margin:0 0 12px; }
        img { display:block; }
        button { font-family: inherit; }
    button, .slide-btn { outline:none; }
    button:focus-visible, .slide-btn:focus-visible { box-shadow: var(--shadow-focus); }
    #ai-summary button { background:var(--color-primary);color:#fff;border:1px solid var(--color-primary);padding:10px 18px;font-size:.9rem;font-weight:500;border-radius:var(--radius-md);cursor:pointer;transition:var(--transition); }
    #ai-summary button:hover { background:var(--color-primary-hover); }
    #ai-summary button:active { transform:scale(.97); }

        /* 헤더 공간 확보 */
        .header-space {
            height: 100px; /* top.html 헤더 높이와 동일 */
        }

    /* 헤더/카테고리/로고 등은 common.css 로 이동 */
    /* 추가 페이지별 전용 스타일만 이 블록에 작성 */

                /* 슬라이드 영역 */
        .slider {
            margin-top: 180px; /* 헤더+카테고리 높이 증가 반영 */
            width: 70%;
            max-width: 1400px; /* 필요 시 확장 */
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            height: 500px; /* 조정된 높이 */
            background:#fff;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
            width: 100%;
        }

        .slide {
            display: flex;
            width: 100%;
            height: 100%;
            flex-shrink: 0;
            background:#fafafa;
        }

        .slide img {
            width: 70%;
            height: 100%;
            object-fit: cover;
        }

        .slide .slide-text {
            width: 30%;
            padding: 24px 24px 24px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg,#ffffff,#f3f6fa);
            box-shadow: inset 0 0 8px rgba(0,0,0,0.05);
        }

    .slide .slide-text h3 { margin:0 0 12px; font-size: 28px; line-height:1.2; }
    .slide .slide-text h3 a { color:inherit; text-decoration:none; }
    .slide .slide-text h3 a:hover { text-decoration:underline; }
        .slide .slide-text p { margin:0; color:#555; }


    .slide-btn { position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.55);color:var(--color-text);border:1px solid var(--color-border);padding:10px 14px;cursor:pointer;border-radius:var(--radius-md);backdrop-filter:blur(4px);box-shadow:var(--shadow-sm);display:flex;align-items:center;justify-content:center;transition:var(--transition); }
    .slide-btn:hover { background:var(--color-primary);color:#fff;border-color:var(--color-primary); }
    .slide-btn:active { transform:translateY(-50%) scale(.96); }
    .prev { left:16px; }
    .next { right:16px; }
    .slide .slide-text h3 { font-size:1.9rem;font-weight:600; }
    .slide .slide-text p { font-size:.95rem;color:var(--color-text-soft);line-height:1.45; }

    /* 뉴스 카드 */
    .card, .news-card, .discussion-card, #ai-summary { background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:16px;box-shadow:var(--shadow-sm);transition:var(--transition); }
    .news-card { flex:1;display:flex;flex-direction:column; }
    .news-card img { width:100%;border-radius:var(--radius-sm);aspect-ratio:16/10;object-fit:cover;margin:8px 0 10px; }
    .news-card h3 { font-size:1rem;margin:0 0 6px;font-weight:600; }
    .news-card p { font-size:.84rem;color:var(--color-text-soft);margin:0; }
    .discussion-card { margin-bottom:12px; }
    .card-hover, .news-card, .discussion-card, #ai-summary { position:relative; }
    .news-card:hover, .discussion-card:hover, #ai-summary:hover { box-shadow:var(--shadow-md);border-color:var(--color-border-strong);transform:translateY(-3px); }

        /* 메인 컨텐츠 2컬럼 레이아웃 */
        .content-wrapper {
            display: flex;
            gap: 24px;
            max-width: 1200px;
            margin: 30px auto 60px;
            padding: 0 16px;
            align-items: flex-start;
        }

        .main-column {
            flex: 1 1 auto;
            min-width: 0; /* flex overflow 방지 */
        }

        .main-column section {
            margin-bottom: 40px;
        }

        .sidebar {
            flex: 0 0 340px;
        }

        #ai-summary {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            padding: 16px;
            position: sticky;
            top: 130px; /* 헤더+카테고리 아래 고정 */
        }

        #ai-summary h2 {
            margin-top: 0;
        }

    .discussion-card p { margin:4px 0;font-size:.88rem; }
    </style>
    <style>
        @media (max-width: 1200px) {
            .slider { width:78%; }
        }
        @media (max-width: 900px) {
            header { height:72px; }
            .category-nav { top:72px; }
            .slider { margin-top:140px;width:88%;height:420px; }
            .slide .slide-text h3 { font-size:1.5rem; }
            .content-wrapper { flex-direction:column; }
            .sidebar { width:100%;flex:initial; }
            #ai-summary { position:static;top:auto;margin-top:0; }
        }
        @media (max-width: 700px) {
            .slide { flex-direction:column; }
            .slide img { width:100%;height:60%; }
            .slide .slide-text { width:100%;height:40%;padding:16px; }
            .slider { height:480px; }
            .header-right { display:none; }
            h2 { font-size:1.25rem; }
        }
    </style>
</head>
<body>

    <?php include 'top.html'; ?>

    <div class="category-nav">
        <a href="#">정치</a>
        <a href="#">시사</a>
        <a href="#">경제</a>
        <a href="#">상식</a>
    </div>

    <!-- 최신 기사 슬라이드 -->
    <div class="slider">
        <div class="slides" id="slides">
            <div class="slide">
                <img src="sample1.jpg" alt="기사1">
                <div class="slide-text">
                    <h3><a href="compare_event.php">헤드라인 1</a></h3>
                    <p>간단한 설명 또는 리드 문장 1.</p>
                </div>
            </div>
            <div class="slide">
                <img src="sample2.jpg" alt="기사2">
                <div class="slide-text">
                    <h3>헤드라인 2</h3>
                    <p>간단한 설명 또는 리드 문장 2.</p>
                </div>
            </div>
            <div class="slide">
                <img src="sample3.jpg" alt="기사3">
                <div class="slide-text">
                    <h3>헤드라인 3</h3>
                    <p>간단한 설명 또는 리드 문장 3.</p>
                </div>
            </div>
            <div class="slide">
                <img src="sample4.jpg" alt="기사4">
                <div class="slide-text">
                    <h3>헤드라인 4</h3>
                    <p>간단한 설명 또는 리드 문장 4.</p>
                </div>
            </div>
            <div class="slide">
                <img src="sample5.jpg" alt="기사5">
                <div class="slide-text">
                    <h3>헤드라인 5</h3>
                    <p>간단한 설명 또는 리드 문장 5.</p>
                </div>
            </div>
        </div>
        <button class="slide-btn prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="slide-btn next" onclick="moveSlide(1)">&#10095;</button>
    </div>

    <script>
let index = 0;
const slides = document.getElementById("slides");
const total = slides.children.length;

function getSlideWidth() {
    // 각 .slide 는 slider 전체 폭(= 현재 70% viewport 폭)을 차지하므로 slider.clientWidth 활용
    const slider = document.querySelector('.slider');
    return slider ? slider.clientWidth : 1000; // 기본값 폴백
}

function showSlide(i) {
    index = (i + total) % total;
    const w = getSlideWidth();
    slides.style.transform = `translateX(${-w * index}px)`;
}

function moveSlide(step) {
    showSlide(index + step);
}

window.addEventListener('resize', () => showSlide(index));

setInterval(() => moveSlide(1), 4000);
    </script>

    <div class="content-wrapper">
        <div class="main-column">
            <!-- 조회수 TOP 3 기사 -->
            <section id="top-articles">
                <h2>TOP ARTICLES</h2>
                <div style="display:flex; gap:20px;">
                    <div class="news-card">
                        <h3>기사1 제목</h3>
                        <img src="top1.jpg" alt="기사1">
                        <p>간단한 요약...</p>
                    </div>
                    <div class="news-card">
                        <h3>기사2 제목</h3>
                        <img src="top2.jpg" alt="기사2">
                        <p>간단한 요약...</p>
                    </div>
                    <div class="news-card">
                        <h3>기사3 제목</h3>
                        <img src="top3.jpg" alt="기사3">
                        <p>간단한 요약...</p>
                    </div>
                </div>
            </section>

            <!-- 투표수 TOP 2 토론 -->
            <section id="top-discussions">
                <h2>TOP DISCUSSIONS</h2>
                <div class="discussion-card">
                    <p>“원화 강세, 수출기업에 유리한가?”</p>
                    <p>찬성: 45 | 반대: 20</p>
                </div>
                <div class="discussion-card">
                    <p>“규제 완화, 스타트업에 긍정적 영향?”</p>
                    <p>찬성: 30 | 반대: 15</p>
                </div>
            </section>
        </div>
        <aside class="sidebar">
            <!-- AI 요약 영역 (오른쪽 사이드바) -->
            <section id="ai-summary">
                <h2>TODAY'S AI ANALYSIS</h2>
                <div id="ai-content">
                    <p>AI가 오늘 주요 기사와 댓글을 분석한 결과가 표시됩니다.</p>
                    <button onclick="loadAISummary()">AI 분석 불러오기</button>
                </div>
            </section>
        </aside>
    </div>

    <script>
function loadAISummary() {
  fetch("ai_summary.php")
    .then(res => res.text())
    .then(data => {
      document.getElementById("ai-content").innerHTML = data;
    });
}
    </script>

    <!-- Dark mode removed; theme toggle eliminated for simplified design -->

</body>
</html>
