<?php
$title = "Список " . $_GET['id'];
define("TITLE", $title);
require($_SERVER["DOCUMENT_ROOT"] . '/system/template/header.php');?>
<div class="card">
    <div class="card-content" id="tabels">
        <div class="card-body">
            <button onclick="downloadCSV();" type="button" class="mr-1 mb-1 btn btn-outline-info btn-min-width">Скачать CSV</button>
            <div class="row" id="channels"></div>
        </div>
    </div>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"] . '/system/template/footer.php');?>
<script>
var container = document.getElementById('channels'),table;
var sizeWidth = parseInt(window.getComputedStyle(document.getElementById('tabels')).width, 10);

function renderTable(bigdata) {
  table = new Handsontable(container, {
    data: bigdata,
    columns: [
      {
        data: 'name',
        type: 'text',
        width: sizeWidth * 0.09
      },
      {
        data: 'subscribers_count',
        type: 'numeric',
        width: sizeWidth * 0.08
      },
      {
        data: 'all_views_count',
        type: 'numeric',
        width: sizeWidth * 0.07
      },
      {
        data: 'youtube_id',
        type: 'text',
        width: sizeWidth * 0.07
      },
      {
        data: 'videos_count',
        type: 'numeric',
        width: sizeWidth * 0.08
      },
      {
        data: 'tags',
        type: 'text',
        wordWrap: true,
        width: sizeWidth * 0.15
      },
      {
        data: 'channel_create_date',
        type: 'date',
        dateFormat: 'YYYY-MM-DD',
        width: sizeWidth * 0.07
      },
      {
        data: 'playlists_count',
        type: 'text',
        width: sizeWidth * 0.07
      },
      {
        data: 'playlists',
        renderer: 'text',
        width: sizeWidth * 0.15
      },
      {
        data: 'top_video_name',
        type: 'text',
        width: sizeWidth * 0.09
      },
      {
        data: 'top_video_views_count',
        type: 'numeric',
        width: sizeWidth * 0.07
      }
    ],
    readOnly: true,
    width: window.getComputedStyle(document.getElementById('tabels')).width,
    autoWrapColumn: true,
    manualRowResize: true,
    manualColumnResize: true,
    colHeaders: [
      'Канал',
      'Подписчики',
      'Просмотры',
      'YouTube ID',
      'Видео',
      'Теги',
      'Создан',
      'Плейлистов',
      'Плейлисты',
      'Топ-видео',
      'Просмотры топ-видео'
    ],
    manualRowMove: true,
    manualColumnMove: true,
    filters: true,
    dropdownMenu: true,
    columnSorting: true,
    sortIndicator: true,
    autoColumnSize: {
      samplingRatio: 23
    }
  });
}

function getList() {
  $.ajax({
    type: 'POST',
    data: { id: <?=$_GET['id'];?> },
    url: '/api/v1/list/getList.api',
    success: function(result){
      result = $.parseJSON(result);
      if (result.status == 'error') {
        toastr.error(data.message, 'Ошибка!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
      } else if (result.status == 'success') {
        renderTable(result.object);
      } else {
        toastr.error('Обратитесь к Администратору', 'Неизвестная ошибка!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
      }
    },
    error: function(xhr, type){
      toastr.error('Ошибка соединения', 'Ошибка!', {
          positionClass: "toast-bottom-center",
          containerId: "toast-bottom-center"
      });
    }
  })
}

function getListInfo() {
  $.ajax({
    type: 'POST',
    data: { id: <?=$_GET['id'];?> },
    url: '/api/v1/list/getListInfo.api',
    success: function(result){
      result = $.parseJSON(result);
      if (result.status == 'error') {
        toastr.error(data.message, 'Ошибка!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
      } else if (result.status == 'success') {
        renderListInfo(result.object[0]);
      } else {
        toastr.error('Обратитесь к Администратору', 'Неизвестная ошибка!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
      }
    },
    error: function(xhr, type){
      toastr.error('Ошибка соединения', 'Ошибка!', {
          positionClass: "toast-bottom-center",
          containerId: "toast-bottom-center"
      });
    }
  })
}

function countWriter(words, count) {
    cases = [2, 0, 1, 1, 1, 2];  
    return count + ' ' + words[ (count%100>4 && count%100<20)? 2 : cases[(count%10<5)?count%10:5] ]; 
}

function renderListInfo(listInfo) {
    $('#listName').html(listInfo.name);
    $('#listBreadcrumbs').html(listInfo.name);
    $('#listChannelsCount').html(countWriter(['канал','канала','каналов'], listInfo.channels_count));
    $('#listLastUpdate').html(listInfo.create_date);
    console.log(listInfo);
}

function downloadCSV() {
    const exportPlugin = table.getPlugin('exportFile');
    exportPlugin.downloadFile('csv', {columnHeaders: true, rowHeaders: false, filename: 'file', columnDelimiter: ';'});
}

function getContentWidth (element) {
  var styles = getComputedStyle(element)

  return element.clientWidth
    - parseFloat(styles.paddingLeft)
    - parseFloat(styles.paddingRight)
}

$(document).ready(function() {
    getListInfo();
    getList();
});
</script>
