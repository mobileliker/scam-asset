var AdminAsset = function(){
  return {
    init: function(){
      $("#left-nav-asset-manager").attr("class", $("#left-nav-asset-manager").attr("class") + " active");
    },

    initIndex: function(){
      Util.initBatchDelete("asset");
    },

    initCreate: function(){
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
          number:{
            required: true,
            remote:{
              url: "/admin/util/check/asset",
              type: "post",
              dataType: "json",
              data:{
                _token: function(){return $("meta[name=csrf-token]").attr("content");},
                field: function(){return $("#number").attr("name");},
                value: function(){return $("#number").val();}
              }
            }
          },
          name:{
            required: true,
            maxlength: 255
          },
          serial:{
            required: true,
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
            required: true
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

    initEdit: function(country, application, consumer_id, handler_id, course) {
      $(".a-select").click(function(){
        //console.log($(this).html());
        $("#" + $(this).attr("data-id")).val($(this).html());
      });

      $(".sum-cal").change(function(){
        $("#sum").val($("#price").val() * $("#amount").val());
      });

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
          number:{
            required: true,
            remote:{
              url: "/admin/util/check/asset",
              type: "post",
              dataType: "json",
              data:{
                _token: function(){return $("meta[name=csrf-token]").attr("content");},
                id : function(){return $("#id").val();},
                field: function(){return $("#number").attr("name");},
                value: function(){return $("#number").val();}
              }
            }
          },
          name:{
            required: true,
            maxlength: 255
          },
          serial:{
            required: true,
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
            required: true
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