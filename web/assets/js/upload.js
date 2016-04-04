function chooseImage()
{
    var file = document.getElementById('fileInput');
    file.click();
}

function hideExportMenu()
{
    $("#exportDiv").css('display', 'none');
}
function showExport()
{
    var exportDiv = $("#exportDiv");
    var isVisible = exportDiv.css('display') == 'none';
    if (!isVisible) {
        hideExportMenu();
    } else {
        $("#exportDiv").css('display', 'block');
    }
}
function reloadContent()
{
    $.get("/getmessages")
        .done(function(data){
            $("#list").html(data.content)
            $("#msgCount").html(data.messagesCount);
            $("#viewsCount").html(data.viewCount);
        })

}

function resetFileInput (e) {
    e.wrap('<form>').parent('form').trigger('reset');
    e.unwrap();
}

function ajaxUpload(fileList, comment)
{
    var formData = new FormData();
    formData.append('file', fileList[0]);
    formData.append('imageTitle', comment);

    $.ajax({
        url: '/newpost',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        method: "POST",
    })
        .done(function( data, textStatus, jqXHR ) {
            reloadContent();
            $("#imageTitle").val("");
            resetFileInput($("#fileInput"));
        })
}

function uploadImage()
{
    var file = document.getElementById('fileInput');
    var comment = $("#imageTitle").val();
    if (file.files.length > 0) {

        ajaxUpload(file.files, comment);
    }

}

function updateCounters() {
    $.get("/getcounts")
        .done(function(data){
            $("#msgCount").html(data.messagesCount);
            $("#viewsCount").html(data.viewCount);
        })
    setTimeout(function(){
        updateCounters();
    }, 15000*60);
}

$("document").ready(function(){
    setTimeout(function(){
        updateCounters();
    }, 15000*60);
})
