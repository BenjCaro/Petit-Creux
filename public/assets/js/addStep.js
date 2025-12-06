document.getElementById('add-step').addEventListener('click', function() {
    const container = document.getElementById('steps-container');
    const stepCount = container.querySelectorAll('.single-step').length + 1;

    const newStep = document.createElement('div');
    newStep.classList.add('single-step', 'mb-3', 'p-3', 'border', 'rounded');

    // Titre
    const title = document.createElement('h5');
    title.classList.add('m-0', 'mb-2');
    title.textContent = `Étape ${stepCount}`;
    newStep.appendChild(title);

    // Label numéro
    const labelNum = document.createElement('label');
    labelNum.htmlFor = "step_number[]"
    labelNum.classList.add('form-label');
    labelNum.textContent = 'Numéro d\'étape';
    newStep.appendChild(labelNum);

    // Input numéro
    const inputNum = document.createElement('input');
    inputNum.type = 'number';
    inputNum.classList.add('form-control');
    inputNum.name = 'step_number[]';
    inputNum.value = stepCount;
    inputNum.required = true;
    newStep.appendChild(inputNum);

    // Label texte
    const labelText = document.createElement('label');
    labelText.htmlFor = "texte[]"
    labelText.classList.add('form-label', 'mt-2');
    labelText.textContent = 'Texte';
    newStep.appendChild(labelText);

    // Textarea
    const textarea = document.createElement('textarea');
    textarea.classList.add('form-control');
    textarea.name = 'texte[]';
    textarea.placeholder = 'Décrivez l\'étape...';
    textarea.required = true;
    newStep.appendChild(textarea);

    // Bouton supprimer
    const divBtn = document.createElement('div');
    divBtn.classList.add('text-end', 'mt-3');

    const btnRemove = document.createElement('button');
    btnRemove.type = 'button';
    btnRemove.classList.add('btn', 'btn-sm', 'btn-danger', 'remove-step');
    btnRemove.textContent = 'Supprimer';

    divBtn.appendChild(btnRemove);
    newStep.appendChild(divBtn);

    container.appendChild(newStep);
    updateStepLabels();
});

// Suppression
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-step')) {
        const step = e.target.closest('.single-step');
        step.remove();
        updateStepLabels();
    }
});

// Mise à jour des labels
function updateStepLabels() {
    const steps = document.querySelectorAll('.single-step');
    steps.forEach((step, index) => {
        const stepNumber = index + 1;
        step.querySelector('h5').textContent = `Étape ${stepNumber}`;
        step.querySelector('input[name="step_number[]"]').value = stepNumber;
    });
}