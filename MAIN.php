<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SH.A.R.K 뉴스 허브</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
        }

        /* 헤더 공간 확보 */
        .header-space {
            height: 100px; /* top.html 헤더 높이와 동일 */
        }

        /* 헤더 */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 0 50px;
        }

        .logo {
            position: absolute;
            left: 48%;
            transform: translateX(-50%);
            font-size: 36px;
            font-weight: bold;
            color: #0077cc;
        }

        .header-right {
            position: absolute;
            right: 150px;
        }

            .header-right a {
                margin-left: 20px;
                text-decoration: none;
                font-weight: bold;
                color: #333;
                font-size: 16px;
            }

        /* 카테고리 메뉴 */
        .category-nav {
            position: fixed;
            top: 100px; /* 헤더 바로 밑 */
            left: 0;
            width: 100%;
            background: #f1f1f1;
            padding: 10px 0;
            text-align: center;
            border-bottom: 1px solid #ddd;
            z-index: 900;
        }

            .category-nav a {
                margin: 0 15px;
                text-decoration: none;
                color: #0077cc;
                font-weight: bold;
                font-size: 16px;
            }

                /* 슬라이드 영역 */
        .slider {
            margin-top: 150px; /* 헤더+카테고리 높이 확보 */
            width: 100%;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            height: 600px; /* 원하는 슬라이드 높이 */
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
            width: 100%;
        }

        .slides img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* 이미지 비율 유지하면서 채우기 */
            flex-shrink: 0;   /* 이미지가 줄어들지 않게 */
        }


        .slide-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        /* 뉴스 카드 */
        .news-card {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            flex: 1;
        }

        /* 섹션 공통 */
        #top-articles, #top-discussions, #ai-summary {
            max-width: 1000px;
            margin: 30px auto; /* 중앙 정렬 복원 */
            padding: 0 16px; /* 작은 화면에서 여백 확보 */
        }

        .discussion-card {
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
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
            <img src="sample1.jpg" alt="기사1">
            <img src="sample2.jpg" alt="기사2">
            <img src="sample3.jpg" alt="기사3">
            <img src="sample4.jpg" alt="기사4">
            <img src="sample5.jpg" alt="기사5">
        </div>
        <button class="slide-btn prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="slide-btn next" onclick="moveSlide(1)">&#10095;</button>
    </div>

    <script>
let index = 0;
const slides = document.getElementById("slides");
const total = slides.children.length;

function getSlideWidth() {
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

    <!-- 조회수 TOP 3 기사 -->
    <section id="top-articles">
        <h2>조회수 높은 뉴스</h2>
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
        <h2>인기 토론</h2>
        <div class="discussion-card">
            <p>“원화 강세, 수출기업에 유리한가?”</p>
            <p>찬성: 45 | 반대: 20</p>
        </div>
        <div class="discussion-card">
            <p>“규제 완화, 스타트업에 긍정적 영향?”</p>
            <p>찬성: 30 | 반대: 15</p>
        </div>
    </section>

    <!-- AI 요약 영역 -->
    <section id="ai-summary">
        <h2>오늘의 AI 분석</h2>
        <div id="ai-content">
            <p>AI가 오늘 주요 기사와 댓글을 분석한 결과가 표시됩니다.</p>
            <button onclick="loadAISummary()">AI 분석 불러오기</button>
        </div>
    </section>

    <script>
function loadAISummary() {
  fetch("ai_summary.php")
    .then(res => res.text())
    .then(data => {
      document.getElementById("ai-content").innerHTML = data;
    });
}
    </script>

</body>
</html>
