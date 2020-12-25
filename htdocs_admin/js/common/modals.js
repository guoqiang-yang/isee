
//通用基础弹框使用.
//  class: _shi_common_dialog [必须]
//  attribute: data-aj-url    [必须]
//             data-width     [可选]
//  smarty_include: {{include file=""}}

(function(){
    $('._shi_common_modals').on('click', function(e){
        var width = parseInt($(this).data('width'))||500;
        var ajaxUrl = $(this).data('aj-url') || '';
        if (ajaxUrl.length === 0) {
            alert('Please Config attribute：data-aj-url');
            return;
        }

        K.post(ajaxUrl, {},
            function(rest){
                $('#_shi_common_modals .custom-modal-text').html(rest.html);

                Custombox.open({
                  target: '#_shi_common_modals',
                  width: width
                });
            },
            function (rest) {
                alert(rest.errmsg);
            }
          );

        e.preventDefault();
  });

})();