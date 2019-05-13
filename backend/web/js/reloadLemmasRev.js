
    $(document).ready(function () {
        $('.list-clicked-rev:first-child').addClass('active');


        $('.tab-clicked-rev').on('click', function () {

            var actual = $(this).get(0).innerText;

            var id_rev_plan = $("#id_rev_plan").get(0).value;

            var id_ext_plan = $("#ext_plan").get(0).value;

            var id_letter = $(this).attr('href').substring(1);

            $('#current-letter').attr('value',id_letter);

            $.ajax({
                url: 'lemmas',
                type: 'Get',
                data: {
                    letter: actual,
                    id_rev_plan: id_rev_plan,
                    id_ext_plan: id_ext_plan
                },
                success:function(data){
                    $("#w0").html(data);
                    //$.pjax.reload({ container : '#lemma_pjax'})
                },
                fail: function(){
                    alert("error")
                }
            });

        });
    });


