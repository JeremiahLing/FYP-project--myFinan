<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Staff Management') }}
        </h2>
    </x-slot>

    <!-- Centering the main content -->
    <div 
        id="backgroundContainer"
        class="min-h-screen text-white flex flex-col items-center py-10 relative overflow-hidden" 
        style="background-color: #CCAAFF;"
    >
        <!-- Fading Background -->
        <div 
            id="backgroundImage" 
            class="transition-all duration-800"
            style="background-size: cover; background-position: center;"
        ></div>

        <!-- Add Staff Button -->
        <div class="mb-6 button-container">
            <button id="openModalBtn" class="history-btn shadow-lg" aria-label="Open Add Staff Modal">
                Add Staff
            </button>
        </div> 

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="text-red-500 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="text-green-500 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-gray-800 flex justify-between items-center mb-4">
                <!-- Search Bar -->
                <input 
                    type="text" 
                    id="searchBar" 
                    placeholder="Search..." 
                    class="border rounded w-1/3"
                >

                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <select id="sortOptions" class="border rounded">
                        <option value="default">Default</option>
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="salary">Salary</option>
                    </select>
                    <button id="sortToggleBtn" class="p-2 border rounded bg-gray-200">
                        Click Me
                    </button>
                </div>
            </div>

            <!-- Staff Table -->
            <table id="staffTable" class="table-auto w-full border-collapse border border-gray-300 rounded-lg text-black relative">
                <thead>
                    <tr class="bg-gray-200" style="background-color: rgba(0, 0, 0, 0.3);">
                        <th class="border border-gray-300 p-2">Staff ID</th>
                        <th class="border border-gray-300 p-2">Name</th>
                        <th class="border border-gray-300 p-2">Email</th>
                        <th class="border border-gray-300 p-2">Phone</th>
                        <th class="border border-gray-300 p-2">IC No</th>
                        <th class="border border-gray-300 p-2">Salary</th>
                        <th class="border border-gray-300 p-2">Position</th>
                        <th class="border border-gray-300 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $member)
                        <tr class="hover-highlight cursor-pointer hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-gray-300 hover:text-white" 
                            data-staff_id="{{ $member->staff_id }}"    
                            data-name="{{ $member->name }}"
                            data-email="{{ $member->email }}"
                            data-phone="{{ $member->phone }}"
                            data-ic_no="{{ $member->ic_no }}"
                            data-salary="{{ $member->salary }}"
                            data-position="{{ $member->position }}"
                            data-photo="{{ asset('storage/' . $member->photo) }}"
                            onmouseover="showBackground(this)"
                            onmouseout="resetBackground()"
                            onclick="openDetailsModal(this)"
                            style="background-color: rgba(0, 0, 0, 0.1);"
                        >
                            <td class="border border-gray-300 p-2">{{ $member->staff_id }}</td>
                            <td class="border border-gray-300 p-2">{{ $member->name }}</td>
                            <td class="border border-gray-300 p-2">{{ $member->email }}</td>
                            <td class="border border-gray-300 p-2">{{ $member->phone }}</td>
                            <td class="border border-gray-300 p-2">{{ $member->ic_no }}</td>
                            <td class="border border-gray-300 p-2">RM {{ number_format($member->salary, 2) }}</td>
                            <td class="border border-gray-300 p-2">{{ $member->position }}</td>
                            <td class="border border-gray-300 p-2 text-center">
                                <div class="button-container">
                                    <!-- Edit Button -->
                                    <button href="javascript:void(0);" 
                                        class="save-btn shadow-lg open-edit-modal" 
                                        data-id="{{ $member->id }}"
                                        data-name="{{ $member->name }}"
                                        data-email="{{ $member->email }}"
                                        data-phone="{{ $member->phone }}"
                                        data-ic_no="{{ $member->ic_no }}"
                                        data-salary="{{ $member->salary }}"
                                        data-position="{{ $member->position }}"
                                        onclick="openEditModal(this, event)">
                                        Edit
                                    </button>

                                    <!-- Delete -->
                                    <div>
                                        <form action="{{ route('staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure want to delete the staff details?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cancel-btn shadow-lg">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-4">No staff records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $staff->links() }}
            </div>
        </div>
    </div>

    <!-- Adding Modal-->
    <div id="modal" class="modal-bg fixed inset-0 bg-gray-800 bg-opacity-70 flex items-center justify-center hidden animate__animated animate__fadeInDown">
        <div class="modal-content bg-white w-full max-w-lg p-6 rounded shadow-lg">
            <h2 class="text-xl font-bold mb-4">Add Staff</h2>

            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <input type="text" name="staff_id" id="staff_id" value="{{ $newStaffId }}" class="p-2 border rounded" readonly>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" class="p-2 border rounded" required>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="p-2 border rounded" required>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" class="p-2 border rounded" required>
                    <input type="text" name="ic_no" value="{{ old('ic_no') }}" placeholder="IC Number" class="p-2 border rounded" required>
                    <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" placeholder="Salary" class="p-2 border rounded" required>
                    <input type="text" name="position" value="{{ old('position') }}" placeholder="Position" class="p-2 border rounded" required>
                </div>
                <div class="button-container mt-4">
                    <button type="button" id="closeModalBtn" class="cancel-btn btn btn-primary shadow-lg">Cancel</button>
                    <button type="submit" class="save-btn btn btn-primary shadow-lg">Add Staff</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-bg fixed inset-0 bg-gray-800 bg-opacity-70 flex items-center justify-center hidden animate__animated animate__fadeInDown">
        <div class="modal-content bg-white w-full max-w-2xl p-6 rounded shadow-lg">
            <h2 class="text-xl font-bold mb-4 text-center">Edit Staff</h2>

            <form id="editStaffForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Two-column layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start mb-4">
                    <div class="grid grid-cols-1 gap-4">
                        <input type="hidden" name="id" id="edit_staff_id">
                        <input type="text" name="name" id="edit_name" placeholder="Name" class="p-2 border rounded" required>
                        <input type="email" name="email" id="edit_email" placeholder="Email" class="p-2 border rounded" required>
                        <input type="text" name="phone" id="edit_phone" placeholder="Phone" class="p-2 border rounded" required>
                        <input type="text" name="ic_no" id="edit_ic_no" placeholder="IC Number" class="p-2 border rounded" required>
                        <input type="number" step="0.01" name="salary" id="edit_salary" placeholder="Salary" class="p-2 border rounded" required>
                        <input type="text" name="position" id="edit_position" placeholder="Position" class="p-2 border rounded" required>
                    </div>

                    <div>
                        <!-- Upload Photo -->
                        <label class="block text-sm font-medium text-gray-700">Staff Photo</label>
                        <input type="file" name="photo" id="edit_photo" class="p-2 border rounded w-full" accept="image/*">
                        <img id="editPhotoPreview" class="w-32 h-32 rounded mt-4" src="#" alt="Photo Preview" style="display: none;">
                    </div>
                </div>

                <div class="p-2 button-container w-full">
                    <button type="button" id="closeEditModalBtn" class="cancel-btn btn btn-primary shadow-lg">Cancel</button>
                    <button type="submit" class="save-btn btn btn-primary shadow-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal-bg fixed inset-0 bg-gray-800 bg-opacity-70 flex items-center justify-center hidden animate__animated animate__fadeInUp">
        <div class="modal-content bg-white w-full max-w-2xl p-6 rounded shadow-lg relative">
            <!-- Close Icon -->
            <button 
                type="button" 
                id="closeDetailsModal" 
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition animate-spin-in"
                aria-label="Close Modal"
            >
                <!-- Cross Icon -->
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-6 w-6" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-4 text-center">Staff Details</h2>

            <!-- Two-column layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                <!-- Photo Section -->
                <div class="flex justify-center">
                    <img id="detailsPhoto" class="w-40 h-48 object-cover border-2 border-gray-300" alt="Staff Photo">
                </div>

                <!-- Details Section -->
                <div>
                    <p><strong>Staff ID:</strong> <span id="detailsStaffID"></span></p>
                    <p><strong>Name:</strong> <span id="detailsName"></span></p>
                    <p><strong>Email:</strong> <span id="detailsEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="detailsPhone"></span></p>
                    <p><strong>IC Number:</strong> <span id="detailsIC"></span></p>
                    <p><strong>Salary:</strong> <span id="detailsSalary"></span></p>
                    <p><strong>Position:</strong> <span id="detailsPosition"></span></p>
                    <p><strong>Birthdate:</strong> <span id="detailsBirthdate"></span></p>
                    <p><strong>Age:</strong> <span id="detailsAge"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        //Background
        function showBackground(row) {
            const photoUrl = row.getAttribute('data-photo');
            const backgroundImage = document.getElementById('backgroundImage');

            // Set the background image
            backgroundImage.style.backgroundImage = `url(${photoUrl})`;

            // Fade in and slide the background image
            backgroundImage.style.opacity = '1';
            backgroundImage.style.transform = 'translateX(0)'; // Ensure it's fully visible
        }

        function resetBackground() {
            const backgroundImage = document.getElementById('backgroundImage');

            // Fade out and slide the background image back
            backgroundImage.style.opacity = '0';
            backgroundImage.style.transform = 'translateX(-100%)'; // Move out of view
        }

        // Close all modals
        function closeAllModals() {
            document.querySelectorAll('.modal-bg').forEach(modal => {
                modal.classList.add('hidden');
            });
        }

        function openDetailsModal(row) {
            // Get data from the clicked row
            const staff_id = row.getAttribute('data-staff_id');
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const phone = row.getAttribute('data-phone');
            const ic_no = row.getAttribute('data-ic_no');
            const salary = row.getAttribute('data-salary');
            const position = row.getAttribute('data-position');
            const photoUrl = row.getAttribute('data-photo');

            // Extract birthdate and calculate age
            const birthDetails = extractBirthdateFromIC(ic_no);
            const birthdate = birthDetails.birthdate;
            const age = birthDetails.age;

            // Populate the modal
            document.getElementById('detailsStaffID').textContent = staff_id;
            document.getElementById('detailsName').textContent = name;
            document.getElementById('detailsEmail').textContent = email;
            document.getElementById('detailsPhone').textContent = phone;
            document.getElementById('detailsIC').textContent = ic_no;
            document.getElementById('detailsSalary').textContent = `RM ${parseFloat(salary).toFixed(2)}`;
            document.getElementById('detailsPosition').textContent = position;
            document.getElementById('detailsBirthdate').textContent = birthdate;
            document.getElementById('detailsAge').textContent = `${age} years`;

            // Set the photo
            const photoElement = document.getElementById('detailsPhoto');
            if (photoUrl) {
                photoElement.src = photoUrl;
                photoElement.alt = `${name}'s Photo`;
            } else {
                photoElement.src = 'default-photo.jpg'; // Provide a default photo URL if none exists
                photoElement.alt = 'Default Photo';
            }

            // Show the modal
            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function extractBirthdateFromIC(ic_no) {
            // Ensure IC number has at least 6 digits for birthdate
            if (ic_no.length < 6) {
                return { birthdate: "Invalid IC", age: "N/A" };
            }

            const yy = parseInt(ic_no.substring(0, 2), 10);
            const mm = parseInt(ic_no.substring(2, 4), 10);
            const dd = parseInt(ic_no.substring(4, 6), 10);

            // Determine full year
            const currentYear = new Date().getFullYear();
            const century = yy > parseInt(currentYear.toString().substring(2, 4)) ? 1900 : 2000;
            const fullYear = century + yy;

            // Validate month and day
            if (mm < 1 || mm > 12 || dd < 1 || dd > 31) {
                return { birthdate: "Invalid IC", age: "N/A" };
            }

            // Construct birthdate
            const birthdate = `${fullYear}-${String(mm).padStart(2, '0')}-${String(dd).padStart(2, '0')}`;

            // Calculate age
            const age = calculateAge(birthdate);

            return { birthdate, age };
        }

        function calculateAge(birthdate) {
            const birthDate = new Date(birthdate);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        // Staff Details Modal
        const detailsModal = document.getElementById('detailsModal');
        document.getElementById('closeDetailsModal').addEventListener('click', () => {
            closeAllModals();
            document.getElementById('detailsModal').classList.add('hidden');
        });

        // Add Staff Modal
        const modal = document.getElementById('modal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');

        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => {
                closeAllModals();
                modal.classList.remove('hidden');
            });
        }

        closeModalBtn.addEventListener('click', () => {
            closeAllModals();
            modal.classList.add('hidden');
        });

        //Edit Modal
        const editModal = document.getElementById('editModal');
        const closeEditModalBtn = document.getElementById('closeEditModalBtn');
        const editForm = document.getElementById('editForm');

        document.getElementById('edit_photo').addEventListener('change', function (event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('editPhotoPreview');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });

        function openEditModal(button, event) {
            // Stop event propagation to avoid triggering row's onclick
            event.stopPropagation();

            document.querySelectorAll('.open-edit-modal').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const phone = this.getAttribute('data-phone');
                    const icNo = this.getAttribute('data-ic_no');
                    const salary = this.getAttribute('data-salary');
                    const position = this.getAttribute('data-position');

                    document.getElementById('edit_staff_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_phone').value = phone;
                    document.getElementById('edit_ic_no').value = icNo;
                    document.getElementById('edit_salary').value = salary;
                    document.getElementById('edit_position').value = position;

                    const editForm = document.getElementById('editStaffForm');
                    editForm.action = `/staff/${id}`;

                    const editModal = document.getElementById('editModal');
                    editModal.classList.remove('hidden');
                });
            });
        }

        document.getElementById('closeEditModalBtn').addEventListener('click', function () {
            document.getElementById('editModal').classList.add('hidden');
        });

        closeEditModalBtn.addEventListener('click', () => {
            closeAllModals();
            editModal.classList.add('hidden');
        });

        //Delete Confirmation Modal (if applicable)
        const deleteModal = document.getElementById('deleteModal'); // Replace with your delete modal ID
        if (deleteModal) {
            const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn'); // Replace with delete modal close button ID
            closeDeleteModalBtn.addEventListener('click', () => {
                closeAllModals();
            });
        }

        // Close modal when clicking outside any modal
        window.addEventListener('click', (e) => {
            document.querySelectorAll('.modal-bg').forEach(modal => {
                if (e.target === modal) {
                    closeAllModals();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            //background image
            const rows = document.querySelectorAll('.row'); // Replace with your row selector
            const backgroundImage = document.getElementById('backgroundImage');

            // Update background image and control opacity
            rows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    const photoUrl = row.getAttribute('data-photo'); // Assuming data-photo contains the image URL
                    if (photoUrl) {
                        backgroundImage.style.backgroundImage = `url(${photoUrl})`;
                        backgroundImage.style.opacity = 1;
                    }
                });

                row.addEventListener('mouseleave', () => {
                    backgroundImage.style.opacity = 0;
                });
            });

            // Adjust position when scrolling
            document.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (backgroundImage) {
                    backgroundImage.style.transform = `translateY(${scrollTop}px)`;
                }
            });

            //Sorting and Searching
            const searchBar = document.getElementById('searchBar');
            const sortOptions = document.getElementById('sortOptions');
            const staffTable = document.querySelector('#staffTable tbody');
            let sortDirection = 'asc';

            // Search functionality
            searchBar.addEventListener('input', () => {
                const query = searchBar.value.toLowerCase();
                const rows = staffTable.querySelectorAll('tr');

                rows.forEach(row => {
                    const cells = Array.from(row.children);
                    const match = cells.some(cell => cell.textContent.toLowerCase().includes(query));
                    row.style.display = match ? '' : 'none';
                });
            });

            // Sort functionality
            sortOptions.addEventListener('change', () => {
                sortDirection = 'asc'; // Reset to ascending when a new option is selected
                sortTable(sortOptions.value, sortDirection);
            });

            // Toggle sort direction on click of the sort button
            document.getElementById('sortToggleBtn').addEventListener('click', () => {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'; // Toggle direction
                sortTable(sortOptions.value, sortDirection);
            });

            // Function to sort the table
            function sortTable(sortBy, direction) {
                if (sortBy === 'default') {
                    location.reload(); // Reload to reset to default order
                    return;
                }

                const rows = Array.from(staffTable.querySelectorAll('tr')).filter(row =>
                    row.querySelector('td') // Ensure it's not a placeholder row
                );

                rows.sort((a, b) => {
                    const aText = getCellValue(a, sortBy).toLowerCase();
                    const bText = getCellValue(b, sortBy).toLowerCase();

                    if (sortBy === 'salary') {
                        const aValue = parseFloat(aText.replace(/[^\d.-]/g, ''));
                        const bValue = parseFloat(bText.replace(/[^\d.-]/g, ''));
                        return direction === 'asc' ? aValue - bValue : bValue - aValue;
                    }

                    return direction === 'asc' ? aText.localeCompare(bText) : bText.localeCompare(aText);
                });

                rows.forEach(row => staffTable.appendChild(row)); // Append sorted rows back to the table
            }

            // Helper function to get cell value based on sort key
            function getCellValue(row, sortBy) {
                const columnMapping = {
                    default: null, // No sorting required
                    name: 1, // Name column index
                    email: 2, // Email column index
                    salary: 5, // Salary column index
                };

                const cellIndex = columnMapping[sortBy];
                return cellIndex !== null ? row.children[cellIndex]?.textContent || '' : '';
            }
        });
    </script>

    <!--Footer-->
    @include('components.footer')
</x-app-layout>