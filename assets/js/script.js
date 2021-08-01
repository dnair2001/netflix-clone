$(document).scroll(function() {
    $(".topBar").toggleClass("scrolled", $(this).scrollTop() > $(".topBar").height());
})

function volumeToggle(button) {
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);

    // checks to see if class is there, if it is then remove it. If it isn't then add that class.
    $(button).find("i").toggleClass("fa-volume-mute");
    $(button).find("i").toggleClass("fa-volume-up");
}

function previewEnded() {
    $(".previewVideo").toggle();
    $(".previewImage").toggle();
}

function goBack() {
    window.history.back();
}

function startHideTimer() {
    var timeout = null;
    
    $(document).on("mousemove", function() {
        clearTimeout(timeout); // clear timer whenever they move the mouse
        $(".watchNav").fadeIn();

        timeout = setTimeout(function() {
            $(".watchNav").fadeOut(); //will hide banner after 2 seconds
        }, 2000);
    })
}

function initVideo(videoId, username) {
    startHideTimer();
    updateProgressTimer(videoId, username);
    setStartTime(videoId, username);
}

function updateProgressTimer(videoId, username) {
    // insert row into sql tbale if it doesn't exist
    addDuration(videoId, username);

    var timer;
    // do this code in here are the video plays
    $("video").on("playing", function(event){
        window.clearInterval(timer);
        timer = window.setInterval(function() {
            updateProgress(videoId, username, event.target.currentTime);
        }, 3000)
    })
    .on("ended", function(){
        setFinished(videoId, username);
        window.clearInterval(timer);
    })
}

function addDuration(videoId, username) {
    // need to have a AJAX call that will go to our PHP file
    // AJAX call sends a request to and from a page
    $.post("ajax/addDuration.php", { videoId: videoId, username: username }, function(data) {
        // if(data !== null && data !== "") {
        //     alert(data);
        // }
    })
}

function updateProgress(videoId, username, progress) {
    $.post("ajax/updateDuration.php", { videoId: videoId, username: username, progress: progress }, function(data) {
        // if(data !== null && data !== "") {
        //     alert(data);
        // }
    })
}


function setFinished(videoId, username) {
    $.post("ajax/setFinished.php", { videoId: videoId, username: username }, function(data) {
        // if(data !== null && data !== "") {
        //     alert(data);
        // }
    })
}

function setStartTime(videoId, username) {
    $.post("ajax/getProgress.php", { videoId: videoId, username: username }, function(data) {
        if(isNaN(data)) {
            alert(data);
            return;
        }

        $("video").on("canplay", function(){
            this.currentTime = data;
            $("video").off("canplay");
        })
    })
}


function restartVideo() {
    $("video")[0].currentTime = 0;
    $("video")[0].play();
    $(".upNext").fadeOut();
}

function watchVideo(videoId) {
    window.location.href = "watch.php?id=" + videoId;
}

function showUpNext() {
    $(".upNext").fadeIn();
}