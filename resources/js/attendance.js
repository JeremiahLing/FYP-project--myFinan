document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.attendance-button').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();

            const staffId = button.getAttribute('data-staff-id');
            const date = button.getAttribute('data-date');
            const svgIcon = button.querySelector('svg');

            fetch('/managements/attendance/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ staff_id: staffId, date: date })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Toggle the icon color
                        if (svgIcon.classList.contains('text-gray-300')) {
                            svgIcon.classList.remove('text-gray-300');
                            svgIcon.classList.add('text-green-500');
                        } else {
                            svgIcon.classList.remove('text-green-500');
                            svgIcon.classList.add('text-gray-300');
                        }
                    } else {
                        alert(data.message || 'Failed to update attendance.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        });
    });
});
