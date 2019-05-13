<div>
    <div id="sources">
        <div class="list-group">
            <?php

            foreach ($sources as $source)
                    echo '
              <a style="font-weight: 600; font-size: 17px;" class="list-group-item source-clicked" href="#">'.$source->name.'</a>
              ';
            ?>
        </div>
    </div>

    <div id="preview"></div>
</div>

<script>
    $('.source-clicked').on('click', function () {

        $('.source-clicked').removeClass('active');

        $('.modal-dialog').removeClass('modal-full').addClass('modal-lg').css({
            'transition': 'all 1500ms ease-in-out'
        });
        var source = $(this).get(0).innerText;

        $(this).addClass('active');

        if (!$(this).hasClass('source-downloaded')) {
            $('.modal-dialog').removeClass('modal-lg').addClass('modal-full').css({
                'transition': 'all 1500ms ease-in-out'
            });
        }

        $.ajax({
            url: 'preview',
            type: 'get',
            data: { source:source },
            success:function(data){
                $('#preview').html(data);
            },
            fail: function(){
                alert("error")
            }
        });
    });

</script>