document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.form-step');
    const nextButtons = document.querySelectorAll('.next-btn');
    const prevButtons = document.querySelectorAll('.prev-btn');
    const content = document.querySelectorAll('.content');


    if(content.length < 30){
        alert('Content must be at least 30 characters long');
        return;
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            const currentStep = button.closest('.form-step');
            const nextStepId = button.getAttribute('data-next-step');
            const nextStep = document.getElementById(`step-${nextStepId}`);
            const input = currentStep.querySelector('input, textarea');
            
            if (input && !input.value.trim()) {
                alert('Please fill in the required field.');
                return;
            }

            currentStep.style.display = 'none';
            nextStep.style.display = 'block';
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            const currentStep = button.closest('.form-step');
            const prevStepId = button.getAttribute('data-prev-step');
            const prevStep = document.getElementById(`step-${prevStepId}`);
            
            currentStep.style.display = 'none';
            prevStep.style.display = 'block';
        });
    });
});
