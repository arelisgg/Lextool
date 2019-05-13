
    $(document).ready(function () {
        $('.list-clicked:first-child').addClass('active');

        $('.tab-clicked').on('click', function () {

            var actual = $(this).get(0).innerText;

            var id_ext_plan = $("#ext_plan").get(0).value;

            var id_letter = $(this).attr('href').substring(1);

            $('#current-letter').attr('value',id_letter);

            //$.pjax.reload({ container : '#lemma_pjax'})

            $.ajax({
                url: 'lemmas',
                type: 'Get',
                data: { letter:actual, id_ext_plan: id_ext_plan },
                success:function(data){
                    $('#w0').html(data);
                },
                fail: function(){
                    alert("error")
                }
            });

        });
    });