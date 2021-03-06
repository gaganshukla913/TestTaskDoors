$(document).on('click','.upload-btn', function(){
    $(this).closest('.row').find('.upload__inputfile').trigger('click');
});

$(document).on('click', ".upload__img-close", function (e) {
  // var file = $(this).parent().data("file");
  $(this).closest('.upload__img-box').remove();
  $i = 0;
  $('.upload__img-wrap').find('.upload__img-box').each(function(){
    $i++;
  });
  if($i == 0){
    $('.upload__img-wrap').text('No File Uploaded');
    $('.upload__img-wrap').addClass('uploaded-file-section');
  }
});

var imgArray = [];
$(document).on('change','.upload__inputfile', function (e) {
    // console.log('dada')
    var imgWrap = $(this).closest('.row').find('.upload__img-wrap');
    $(this).closest('.row').find('.upload__img-wrap').removeClass('uploaded-file-section');
    var maxLength = $(this).attr('data-max_length');

    var files = e.target.files;
    var filesArr = Array.prototype.slice.call(files);
    console.log(filesArr);
    var iterator = 0;
    imgWrap.html('');
    filesArr.forEach(function (f, index) {
      if (imgArray.length > maxLength) {
        return false;
      } else {
        var len = 0;
        for (var i = 0; i < imgArray.length; i++) {
          if (imgArray[i] !== undefined) {
            len++;
          }
        }
        if (len > maxLength) {
          return false;
        } else {
          imgArray.push(f);

          var reader = new FileReader();
          reader.onload = function (e) {
                console.log(f.type.match('application/*'))      
            var html = '';
            if( f.type.match('application.pdf')){
              // console.log('application');
              html = "<div class='upload__img-box'><div style='background-image: url(https://cdn-icons-png.flaticon.com/512/732/732220.png?w=360)' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
            }else{
              html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
            }
                console.log(html);
            imgWrap.append(html);

            iterator++;
          }
          reader.readAsDataURL(f);
        }
      }
    });
  });


$(document).on('click','.add',function(){
  var id = parseInt($(this).attr('data-id'))+1;
  $(this).attr('data-id',id);
  $('.total_row').val(id);
  var row = '';
  row += '<div class="row">';
  row += '      <div class="col-md-3">';
  row += '  <label for="">Title</label>';
  row += '  <input type="text" class="form-control attribute-title" name="titleNew_'+id+'"><span class="attribute-title-error text-danger"></span>';
  row += '</div>';
  row += '<div class="col-md-3">';
  row += '  <label for="">Price</label>';
  row += '  <div div class="input-group">';
  row += '       <div class="input-group-prepend">';
  row += '          <span class="input-group-text" id="basic-addon1">$</span>';
  row += '      </div>';
  row += '      <input type="text" class="form-control attribute-price" name="priceNew_'+id+'"><span class="attribute-price-error text-danger"></span>';
  row += '  </div>';
  row += '</div>';
  row += '<div class="col-md-4">';
  row += '  <div class="uploaded-file-section upload__img-wrap">';
  row += '      No File uploaded';
  row += '   </div> <span class="attribute-image-error text-danger"></span>';
  row += '</div>';
  row += '<div class="col-md-2 mt-4">';
  row += '   <a href="javascript:;" class="btn btn-danger remove-row" data-toggle="tooltip" title="Remove">';
  row += '      <i class="fa fa-minus"></i>';
  row += '  </a>';
  row += '  <a href="javascript:;" class="btn btn-primary upload-btn" data-toggle="tooltip" title="Upload File">';
  row += '      <i class="fa fa-upload"></i>';
  row += '  </a>';
  row += '  <input type="file" multiple="" data-max_length="20" class="upload__inputfile attribute-image d-none" name="fileNew_'+id+'[]">';
  row += ' </div>';
  row += '</div>';
  $('.row-data').append(row);
  $('[data-toggle="tooltip"]').tooltip()
});

$(document).on('click','.remove-row',function(){
  var id = parseInt($('.add').attr('data-id'))-1;
  $('.add').attr('data-id',id);
  $('.total_row').val(id);
  var current_id = $(this).attr('data-id');
  if(current_id){
    $previousData =$('textarea[name="remove-id"]').val() ? $('textarea[name="remove-id"]').val()+',' : '' ; 
    $('textarea[name="remove-id"]').val($previousData+current_id);
  }
  $(this).closest('.row').remove();
})


// VALIDATION PART
$(document).on('click','.submit-button',function() {
  var flag = true;
  if($('.name').val()){
    $('.name-error').html('');
  }else{
    $('.name-error').html('This field is required');
    flag = false;
  }

  if($('.type').val()){
    $('.type-error').html('');
  }else{
    $('.type-error').html('This field is required');
    flag = false;
  }

  $('.row-data').find('.row').each(function(index,value){
      var image = $(this).find('.attribute-image').val();
      var title = $(this).find('.attribute-title').val();
      var price = $(this).find('.attribute-price').val();
      if(title){
        $(this).closest('.row').find('.attribute-title-error').html('');
      }else{
        $(this).closest('.row').find('.attribute-title-error').html('This field is required');
        flag = false;
      }
      if(price){
        $(this).closest('.row').find('.attribute-price-error').html('');
      }else{
        $(this).closest('.row').find('.attribute-price-error').html('This field is required');
        flag = false;
      }
      console.log(price);
      // if(buttonType == 'save') {
        if(image || image == undefined ){
          $(this).closest('.row').find('.attribute-image-error').html('');
        }else{
          $(this).closest('.row').find('.attribute-image-error').html('This field is required');
          flag = false;
        }
      // }
  });


  if(flag) {
    $('#form_attribute_id').submit();
  }
});

$(document).on('change','.type',function(){
var row = '';
row += '<div class="row">';
row += '<div class="col-md-3">';
row += '<label for="">Title</label>';
row += '<input type="text" class="form-control attribute-title" name="titleNew_1">';
row += '<span class="attribute-title-error text-danger"></span>';
row += '  </div>';
row += ' <div class="col-md-3">';
row += '      <label for="">Price</label>';
row += '          <div class="input-group">';
row += '             <div class="input-group-prepend">';
row += '                <span class="input-group-text" id="basic-addon1">$</span>';
row += '           </div>';
row += '        <input type="text" class="form-control attribute-price" name="priceNew_1">';
row += '      </div>';
row += '    <span class="attribute-price-error text-danger"></span>';
row += '  </div>';
row += ' <div class="col-md-4">';
row += '     <div class="uploaded-file-section upload__img-wrap">';
row += '         No File uploaded';
row += '     </div>';
row += '    <span class="attribute-image-error text-danger"></span>';
row += ' </div>';
row += '<div class="col-md-2 mt-4">';
row += '   <a href="javascript:;" class="btn btn-primary add" data-toggle="tooltip" title="Add" data-id="1">';
row += '      <i class="fa fa-plus"></i>';
row += '  </a>';
row += ' <a href="javascript:;" class="btn btn-primary upload-btn" data-toggle="tooltip" title="Upload File">';
row += '      <i class="fa fa-upload"></i>';
row += ' </a>';
row += '      <input type="file" multiple="" name="fileNew_1[]" data-max_length="20" class="upload__inputfile attribute-image d-none">';
row += '  </div>';
row += '</div>';
  if($(this).val() == 'input'){
    $('.row-data').html(' ');
  }else{
    $('.row-data').html(row);
  }
});

