<h1>{{TITLE}}</h1>

<div class="goods">
    {{CATALOG}}
</div>

<form method="post" enctype="multipart/form-data" class="goods_form">
    <fieldset>
        <legend>Добавление товара</legend>
        <label for="goods_img">Изображение товара: </label>
        <input type="file" name="image_name" id="goods_img">
        <br>
        <label for="goods_name">Название товара: </label>
        <input type="text" name="name" id="goods_name">
        <br>
        <label for="goods_desc">Описание товара: </label>
        <input type="text" name="desc" id="goods_desc">
        <br>
        <label for="goods_price">Цена товара: </label>
        <input type="number" name="price" id="goods_price">
        <br>
        <input type="submit" value="Добавить">
    </fieldset>
</form>