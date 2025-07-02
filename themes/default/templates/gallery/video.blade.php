@extends('theme::layout')

@section('content')



<section id="include">
    <div class="container">
        <div class="row ">
            <div class="col-sm-8 k0test data-id-{{ @$gallery->id }}">
                <h1 class="text-center video-title">{{ $gallery->name }}</h1>
                <div class="content">
                    {!! @$gallery->content['content'] !!}

                    @if(\App\Libraries\Str::isYoutubeLink(@$gallery->content['link']))
                    <div class="embed-responsive embed-responsive-4by3">
                        <iframe class="embed-responsive-item vjs-16-9" src="{{ \App\Libraries\Str::parseYoutubeLinkToEmbed(@$gallery->content['link']) }}" frameborder="0" allowfullscreen></iframe>
                    </div>
		    @else


			    @if($gallery->id == 1077)

<video id="videoPlayer" controls autoplay poster="thumbnail-default.jpg">
    <source src="" type="video/mp4" id="videoSource">
    Your browser does not support the video tag.
</video>
<img id="logo" src="https://baohaiquanvietnam.vn/storage/images/logo1.png?t=40238471a77" alt="Baohaiquan Vietnam logo">
<div id="playlist">
    <ul id="playlistItems">
        <!-- Playlist items will be added here dynamically -->
    </ul>
</div>

			    @endif

			   @if ($gallery->id != 1077) 


                        <video id="my-video" align="center"  class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" width="640" height="264"  controls poster="{{ @$gallery->thumbnail }}" preload="auto" width="780" height="450" data-setup='{"fluid": true}'>
                            <source src="{{ @$gallery->content['link'] }}" type='video/mp4'>
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                            </p>
			</video>
			@endif
                    @endif
                </div>
                <div class="meta">
                    <span class="updated_at">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        {!! trans('gallery::web.published_at', ['datetime' => $gallery->gallery->published_at->format('d-m-Y h:s')])  !!}
                    </span>
                    <span class="views">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        {!! trans('gallery::web.view_count', ['count' => $gallery->gallery->view->count]) !!}
                    </span>
                </div>
                <div class="title_other" style="position:relative">
                    <h2 class="widget-title"><span class="icon"></span><span>Video Liên Quan</span></h2>
                  </div>
                  
                  @include('theme::partial.other_video',['category_id' => $gallery->gallery->categories->map->id->toArray()])
            </div>
            <div class="col-sm-4 navleft destop-pr0 tablet-pr0">
              @include('theme::partial.slidebar')
            </div>
        </div>
    </div>
</section>




@if($gallery->id == 1077)
    <script>
        // Đoạn JavaScript bạn muốn chèn khi ID của gallery là 1071
        // Ví dụ:


var videoList = [
        {url: "https://data.baohaiquanvietnam.vn/Music/0-Gioi%20thieu%20DVD%20Vinh%20quang%20nguoi%20chien%20si%20ky%20thuat%20Hai%20quan.mp4", title: "Giới thiệu DVD Vinh quang người chiến sĩ kỹ thuật Hải quân"},
        {url: "https://data.baohaiquanvietnam.vn/Music/1.%20Lanh%20hai%20thieng%20lieng.mp4", title: "Lãnh hải thiêng liêng"},
        {url: "https://data.baohaiquanvietnam.vn/Music/2.%20Gui%20anh%20nguoi%20chien%20si%20Hai%20quan.mp4", title: "Gửi anh người chiến sĩ Hải quân"},
        {url: "https://data.baohaiquanvietnam.vn/Music/3.%20To%20quoc%20trong%20tim%20nguoi%20linh%20tho%20Hai%20quan.mp4", title: "Tổ quốc trong tim người lính thợ Hải quân"},
        {url: "https://data.baohaiquanvietnam.vn/Music/4.%20Khuc%20ca%20nganh%20vat%20tu%20Hai%20quan.mp4", title: "Khúc ca ngành vật tư Hải quân"},
        {url: "https://data.baohaiquanvietnam.vn/Music/5.%20Truong%20Sa%20oi.mp4", title: "Trường Sa ơi"},
        {url: "https://data.baohaiquanvietnam.vn/Music/6.%20Bien%20ta%20vang%20mai%20tieng%20con%20tau.mp4", title: "Biển ta vang mãi tiếng con tàu"},
        {url: "https://data.baohaiquanvietnam.vn/Music/7.%20Nghe%20em%20hat%20o%20Truong%20Sa.mp4", title: "Nghe em hát ở Trường Sa"},
        {url: "https://data.baohaiquanvietnam.vn/Music/8.%20Binh%20minh%20tren%20cang%20Vung%20Bau.mp4", title: "Bình minh trên cảng Vũng Bầu"},
	{url: "https://data.baohaiquanvietnam.vn/Music/9.%20Tinh%20em%20nguoi%20tho.mp4", title: "Tình em người thợ"},
	{url: "https://data-baohaiquan@data.baohaiquanvietnam.vn/NAM%202024/CLIP/11.%20Niem%20tin%20nguoi%20linh%20khi%20tai%20dien%20tu%20Hai%20quan.mp4", title: "Niềm tin người lính khí tài điện tử Hải quân"},
	{url: "https://data.baohaiquanvietnam.vn/NAM%202024/CLIP/12.%20Vinh%20quang%20nguoi%20chien%20si%20ky%20thuat%20Hai%20quan.mp4", title: "Vinh quang người chiến sĩ kỹ thuật Hải quân"}
    ];
    var videoPlayer = document.getElementById('videoPlayer');
    var videoSource = document.getElementById('videoSource');
    var playlistItems = document.getElementById('playlistItems');
    // Set default video
    videoSource.src = videoList[0].url;
    videoPlayer.load();
    // Create playlist items
    videoList.forEach(function(video, index) {
        var listItem = document.createElement('li');
        var link = document.createElement('a');
        var number = document.createElement('span');
        link.href = '#';
        link.textContent = video.title;
        // Thêm số thứ tự vào tiêu đề bài hát
        number.textContent = (index + 1);
        number.classList.add('number');
        link.insertBefore(number, link.firstChild);
        link.onclick = function() {
            videoSource.src = video.url;
            videoPlayer.load();
            videoPlayer.play();
            return false;
        };
        listItem.appendChild(link);
        playlistItems.appendChild(listItem);
    });


    </script>




<style>
    #videoPlayer {
        width: 100%;
        margin-bottom: 20px;
    }
    #logo {
        position: absolute;
        top: 80px;
        right: 20px;
        width: 200px;
        height: auto;
        z-index: 1000;
    }
    #playlist {
        margin: 0 auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #playlistItems {
        list-style-type: none;
        padding: 0;
        margin: 0;
        overflow-y: auto;
       
    }
    #playlistItems li {
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
    }
    #playlistItems li:hover {
        background-color: #f9f9f9;
    }
    #playlistItems li:last-child {
        border-bottom: none;
    }
    #playlistItems li a {
        color: #333;
        text-decoration: none;
        font-size: 16px;
        margin-left: 10px;
        position: relative;
    }
    #playlistItems li a .number {
        background-color: #007bff;
        color: #fff;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: inline-block;
        text-align: center;
        line-height: 25px;
        margin-right: 10px;
        font-size: 14px;
    }
    #playlistItems li a:hover .number {
        background-color: #0056b3;
    }
</style>



@endif



@endsection
