<h1>{{TITLE}}</h1>

<form method="post" class="calc">
    <fieldset>
        <legend>Калькулятор</legend>
        <input type="number" name="number1">
        <select name="operation" id="">
            <option value="sum">Сложить</option>
            <option value="diff">Вычесть</option>
            <option value="mult">Умножить</option>
            <option value="div">Разделить</option>
        </select>
        <input type="number" name="number2">
        <input type="submit" value="Расчитать">
    </fieldset>
    <div class="result">{{RESULT}}</div>
</form>