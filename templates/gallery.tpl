<h2>Галлерея</h2>

{{SLIDER}}
<form action="." method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept="image/jpeg, image/png">
    <button type="submit">Отправить</button>
</form>

<script src="../js/jquery.js"></script>
<script src="../js/slick.min.js"></script>
<script>
    $('.slider').slick(
        {
            arrows: true,
            dots: true,
            slidesToShow: 3,
            slidesToScroll: 1
        }
    );
</script>