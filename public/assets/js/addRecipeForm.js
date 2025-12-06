const steps = document.querySelectorAll(".step");
let currentStep = 0;

document.querySelectorAll(".next").forEach(btn => {
    btn.addEventListener("click", () => {
        steps[currentStep].classList.remove("active");
        currentStep++;
        steps[currentStep].classList.add("active");
    });
});

document.querySelectorAll(".prev").forEach(btn => {
    btn.addEventListener("click", () => {
        steps[currentStep].classList.remove("active");
        currentStep--;
        steps[currentStep].classList.add("active");
    });
});

