document.getElementById('color').addEventListener('input', function () {
    document.documentElement.style.setProperty('--primary-color', this.value);
});

document.getElementById('template').addEventListener('change', function () {
    const preview = document.getElementById('template-preview');
    preview.className = `invoice-template ${this.value}`;
});

document.getElementById('logo').addEventListener('change', function (event) {
    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById('logo-preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
});

// Preview Button Logic
document.getElementById('preview-button').addEventListener('click', function () {
    const selectedTemplate = document.getElementById('template').value;
    const selectedColor = document.getElementById('color').value;

    // Apply template class
    const preview = document.getElementById('template-preview');
    preview.className = `invoice-template ${selectedTemplate}`;

    // Apply primary color
    document.documentElement.style.setProperty('--primary-color', selectedColor);

    // Preview logo if uploaded
    const logoInput = document.getElementById('logo');
    const previewLogo = document.getElementById('preview-logo');
    if (logoInput.files && logoInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewLogo.src = e.target.result;
            previewLogo.style.display = 'block';
        };
        reader.readAsDataURL(logoInput.files[0]);
    } else {
        previewLogo.style.display = 'none'; // Hide if no logo uploaded
    }
});
