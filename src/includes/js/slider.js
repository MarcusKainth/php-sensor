function createSlider(lowTemp, highTemp) {
    var slider = document.getElementById("temp_slider");
        noUiSlider.create(slider, {
            start: [lowTemp, highTemp],
            connect: true,
            step: 1,
            range: {
                'min': 0,
                'max': 60
            },
            format: wNumb({
                decimals: 0
            })
        });

    var low = document.getElementById("low");
    var high = document.getElementById("high");

    slider.noUiSlider.on('update', function( values, handle ) {
        var value = values[handle];
        if ( handle ) {
            high.value = value;
        } else {
            low.value = Math.round(value);
        }
    });

    low.addEventListener('change', function(){
        slider.noUiSlider.set([this.value, null]);
    });

    high.addEventListener('change', function(){
        slider.noUiSlider.set([null, this.value]);
    });

    $("#temp_slider").mouseup(function() {
        $.post("tempscale.php", {low: low.value, high: high.value});
    });
}
