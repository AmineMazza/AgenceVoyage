let defaultTransform = 0;

function goNext() {
    defaultTransform = defaultTransform + 398;
    var slider = document.getElementById("slider");
    if (Math.abs(defaultTransform) >= slider.scrollWidth / 1.7) defaultTransform = 0;
    slider.style.transform = "translateX(" + defaultTransform + "3150px)";
    }
next.addEventListener("click", goNext);

function goPrev() {
    var slider = document.getElementById("slider");
    if (Math.abs(defaultTransform) === 0) defaultTransform = 0;
    else defaultTransform = defaultTransform - 398;
    slider.style.transform = "translateX(" + defaultTransform + "px)";
    }
prev.addEventListener("click", goPrev);
