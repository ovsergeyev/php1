<h1>{{TITLE}}</h1>

<form method="post" class="calc">
    <fieldset>
        <legend>Калькулятор</legend>
        <input type="number" name="number1">
        <input type="hidden" name="operation" class="operation" value="sum">
        <button class="sum active">Сложить</button>
        <button class="diff">Вычесть</button>
        <button class="mult">Умножить</button>
        <button class="div">Разделить</button>
        <input type="number" name="number2">
        <input type="submit" value="Расчитать">
    </fieldset>
    <div class="result">{{RESULT}}</div>
</form>

<script>
    const $button_sum  = document.querySelector('.sum');
    const $button_diff = document.querySelector('.diff');
    const $button_mult = document.querySelector('.mult');
    const $button_div  = document.querySelector('.div');
    const $operation   = document.querySelector('.operation');

    $button_sum.addEventListener("click", (event)=>{
        $operation.value = "sum";
        let $buttons = document.querySelectorAll('button');
        $buttons.forEach(($button) => {
            $button.classList.remove('active');
        });
        $button_sum.classList.add('active');
        event.preventDefault();
    });

    $button_diff.addEventListener("click", (event)=>{
        $operation.value = "diff";
        let $buttons = document.querySelectorAll('button');
        $buttons.forEach(($button) => {
            $button.classList.remove('active');
        });
        $button_diff.classList.add('active');
        event.preventDefault();
    });

    $button_mult.addEventListener("click", (event)=>{
        $operation.value = "mult";
        let $buttons = document.querySelectorAll('button');
        $buttons.forEach(($button) => {
            $button.classList.remove('active');
        });
        $button_mult.classList.add('active');
        event.preventDefault();
    });

    $button_div.addEventListener("click", (event)=>{
        $operation.value = "div";
        let $buttons = document.querySelectorAll('button');
        $buttons.forEach(($button) => {
            $button.classList.remove('active');
        });
        $button_div.classList.add('active');
        event.preventDefault();
    });
</script>