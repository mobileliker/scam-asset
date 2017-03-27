var AdminAsset = function(){
  return {
    init: function(){
      $("#left-nav-asset-manager").attr("class", $("#left-nav-asset-manager").attr("class") + " active");
    },

    initIndex: function(type){
      $("#type").val(type);

      Util.initBatchDelete("asset");

      $("#a-admin-asset-import").click(function () {
        layer.open({
          type: 1,
          title: '固定资产导入',
          skin: 'layui-layer-rim',
          area: ['600px', '300px'],
          content: $('#admin-asset-import')
        });
      });

      $(".a-admin-asset-image").click(function() {
        var image = $(this).attr('data-image');
        //console.log(image);
        //('#admin-asset-image img').attr("src", "/storage/upload/image/bdeec66746ae08ce41d0e8c7a9f44df9.jpg");
        $('#admin-asset-image img').attr("src", image);
        $('#admin-asset-image a').attr("href", image);

        var layer_image = layer.open({
          type: 1,
          title: false, //不显示标题
          area: '720px',
          content: $('#admin-asset-image')
        });
        //layer.iframeAuto(layer_image);

      });

      $(".a-admin-asset-qrcode").click(function() {
        var id = $(this).attr('data-id');
        var link = "/admin/asset/" + id + "/qrcode";

        var image = $(this).attr('data-image');
        $('#admin-asset-qrcode img').attr("src", image);
        $('#admin-asset-qrcode a').attr("href", image);

        var layer_image = layer.open({
          type: 1,
          title: false, //不显示标题
          area: '720px',
          content: $('#admin-asset-qrcode')
        });
        //layer.iframeAuto(layer_image);

      });
      
      $("#btn-import-save").click(function() {
        $("#form-asset").validate({
          rules:{
            file:{
              required: true
            }
          },
          submitHandler: function () {
            $('#form-asset').ajaxSubmit(function(data) {
              //console.log(data);
              if(data == "false"){
                layer.closeAll();
                layer.msg('导入失败');
              }else{
                layer.closeAll();
                layer.msg('保存成功');
                history.go(0);
              }
            });
          }
        });
      });
    },

    initCreate: function(){
      $("#course").val("自筹");

      $(".a-select").click(function(){
        //console.log($(this).html());
        $("#" + $(this).attr("data-id")).val($(this).html());
      });

      $(".sum-cal").change(function(){
        $("#sum").val($("#price").val() * $("#amount").val());
      });


      $("#form-asset").validate({
        rules:{
          post_date:{
            required: true,
            date: true
          },
          name:{
            required: true,
            maxlength: 255
          },
          serial:{
            maxlength: 255
          },
          course:{
            required: true,
            maxlength: 255
          },
          model:{
            required: true,
            maxlength: 255
          },
          size:{
            required: true,
            maxlength: 255
          },
          consumer_company:{
            required: true,
            maxlength: 255
          },
          factory: {
            required: true,
            maxlength: 255
          },
          provider: {
            required: true,
            maxlength: 255
          },
          country: {
            required: true
          },
          storage_location: {
            required: true,
            maxlength: 255
          },
          application: {
            required: true,
            maxlength: 255
          },
          invoice: {
            required: true,
            maxlength: 255
          },
          purchase_number: {
          },
          purchase_date: {
            required: true,
            date: true
          },
          card: {
            required: true
          },
          price: {
            required: true,
            number: true,
            min: 0
          },
          amount: {
            required: true,
            digits: true,
            min: 0
          },
          entry: {
            required: true,
            maxlength: 255
          },
          consumer_id: {
            required: true,
            digits: true,
            min: 0
          },
          handler_id: {
            required: true,
            digits: true,
            min: 0
          }
        }
      });
    },

    initEdit: function(category_number, type, country, application, consumer_id, handler_id, course) {
      $(".a-select").click(function(){
        //console.log($(this).html());
        $("#" + $(this).attr("data-id")).val($(this).html());
      });

      $(".sum-cal").change(function(){
        $("#sum").val($("#price").val() * $("#amount").val());
      });

      $("#category_number").val(category_number);
      $("#type").val(type);
      $("#country").val(country);
      $("#application").val(application);
      $("#consumer_id").val(consumer_id);
      $("#handler_id").val(handler_id);
      $("#course").val(course);


      $("#form-asset").validate({
        rules:{
          post_date:{
            required: true,
            date: true
          },
          name:{
            required: true,
            maxlength: 255
          },
          serial:{
            maxlength: 255
          },
          course:{
            required: true,
            maxlength: 255
          },
          model:{
            required: true,
            maxlength: 255
          },
          size:{
            required: true,
            maxlength: 255
          },
          consumer_company:{
            required: true,
            maxlength: 255
          },
          factory: {
            required: true,
            maxlength: 255
          },
          provider: {
            required: true,
            maxlength: 255
          },
          country: {
            required: true
          },
          storage_location: {
            required: true,
            maxlength: 255
          },
          application: {
            required: true,
            maxlength: 255
          },
          invoice: {
            required: true,
            maxlength: 255
          },
          purchase_number: {
          },
          purchase_date: {
            required: true,
            date: true
          },
          card: {
            required: true
          },
          price: {
            required: true,
            number: true,
            min: 0
          },
          amount: {
            required: true,
            digits: true,
            min: 0
          },
          entry: {
            required: true,
            maxlength: 255
          },
          consumer_id: {
            required: true,
            digits: true,
            min: 0
          },
          handler_id: {
            required: true,
            digits: true,
            min: 0
          }
        }
      });
    }
  };
}();