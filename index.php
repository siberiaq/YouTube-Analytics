<?php
define("TITLE", "Управление списками");
require($_SERVER["DOCUMENT_ROOT"] . '/system/template/header.php'); ?>
  <section>
    <div class="card">
      <div class="card-header" style="padding-bottom: 0; padding-left: 0rem;">
        <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
          <!-- Modal -->
          <div class="modal animated bounceInDown text-left" id="bounceInDown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel47" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel47">Создание нового списка</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
                <fieldset class="form-group">
                  <label>Название списка</label>
                  <input type="text" class="form-control" id="NewListName" value="" placeholder="Название списка">
                </fieldset>
                <label>Список ссылок</label></br>
                <small class="text-muted">Разделителем ссылок могуть быть <code>перенос строки</code>, <code>,</code>, <code>;</code> или <code>пробел</code>.</small>
                </br></br>
                <div id="editor"></div>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Закрыть</button>
              <button type="button" id="SendList" class="btn btn-outline-primary">Создать</button>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-content" style="padding-left: 10px; padding-right: 10px; margin-top: -24px;">
        <div class="card-body">
            <button type="button" class="btn btn-outline-blue mb-2 blue" data-toggle="modal" data-target="#bounceInDown">
            Создать новый список
            </button>
            <div id="new-list">
                <input type="text" class="search form-control border-blue mb-1" placeholder="Поиск" />
                    <ul class="nav col-lg-6 col-sm-12 nav-pills nav-active-blue nav-justified">
						<li class="nav-item">
							<a class="sort nav-link mb-2 mr-2 active" data-toggle="pill" href="#" data-sort="create_date">По дате изменения</a>
						</li>
						<li class="nav-item">
							<a class="sort nav-link mb-2 mr-2" data-toggle="pill" href="#" data-sort="name">По имени</a>
						</li>
						<li class="nav-item">
							<a class="sort nav-link mb-2" data-toggle="pill" href="#" data-sort="channels_count">По количеству каналов</a>
						</li>
					</ul>
                <ul class="list-group list"></ul>
             </div>
            <div class="row" id="lists"></div>
        </div>
    </div>
        </div>
</section>
<?php require($_SERVER["DOCUMENT_ROOT"] . '/system/template/footer.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.3.3/ace.js" type="text/javascript" charset="utf-8"></script>
<script>   
var container = document.getElementById('lists'),table;
var editor = ace.edit("editor");

var options = {
        valueNames: [
            'name',
            'create_date',
            {name: 'href', attr: 'href'},
            'channels_count'
         ],
        item: '<li><div class="card bg-gradient-x-info"><div class="card-content"><a class="href" href="#"><div class="row"><div class="col-lg-7 col-sm-12 border-right-info border-right-lighten-3"><div class="card-body text-left"><h2 class="name text-bold-600 white"></h2></div></div><div class="col-lg-2 col-sm-12 border-right-info border-right-lighten-3"><div class="card-body text-left"><h2 class="channels_count text-bold-400 white"></h2><span class="text-white bottom">Каналов</span></div></div><div class="col-lg-3 col-sm-12"><div class="card-body text-left"><h2 class="create_date text-bold-400 white"></h2><span class="text-white">Изменен</span></div></div></div></a></div></div></li>'
    };

var lists = new List('new-list', options);

function getLists() {
  $.ajax({
    type: 'POST',
    url: '/api/v1/lists/getLists.api',
    success: function(result){
      result = $.parseJSON(result);
      if (result.status == 'error') {
        toastr.error(data.message, 'Ошибка!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
      } else if (result.status == 'success') {
          lists.add(result.object);
          lists.sort('create_date', { order: "desc" });
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

$('#SendList').on('click', function(e){
  $.ajax({
    type: 'POST',
    data: {name: $('#NewListName').val() , list: editor.getValue()},
    url: '/api/v1/lists/createList.api',
    success: function(result){
      result = $.parseJSON(result);
      if (result.status == 'error') {
        toastr.error(result.message, 'Ошибка!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
      } else if (result.status == 'success') {
        lists.clear();
        toastr.success(result.message, 'Успешно!', {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        });
        getLists();
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
  });
})

$(document).ready(function(){
    getLists();
});
</script>
