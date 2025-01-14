<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Budget Management') }}
        </h2>
    </x-slot>

    <!-- Centering the main content -->
    <div class="py-12 w-full flex items-center justify-center" style="background-color: #CCAAFF;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Text Container with limited width -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 max-w-md mx-auto p-6 text-center animate__animated animate__fadeInDown" style="background-color: rgba(255, 0, 255, 0.9);">
                @if (Auth::check())
                    <p>Welcome to the Budget Management side!</p>
                @else
                    <p>Please log in to see your profile.</p>
                @endif
            </div>

            <!-- Form Container centered and styled -->
            <div class="form-container mx-auto animate__animated animate__fadeInUp">
                <form action="{{ route('budget.store') }}" method="POST">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-row">
                        <div>
                            <label for="item_id">Item ID</label>
                            <input type="text" name="item_id" id="item_id" value="{{ $nextItemId }}" readonly>
                        </div>
                        <div>
                            <label for="item_name">Item Name</label>
                            <input type="text" name="item_name" id="item_name" placeholder="Item Name" required>
                        </div>
                        <div>
                            <label for="quantity">Quantity</label>
                            <input type="text" name="quantity" id="quantity" placeholder="Quantity" required>
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Enter description here..."></textarea>

                    <div class="form-row">
                        <div>
                            <label for="time">Time</label>
                            <div class="time-container">
                                <input type="time" name="time" id="time" placeholder="HH: MM: SS">
                            </div>
                        </div>
                        <div>
                            <label for="date">Date</label>
                            <div class="date-container">
                                <input type="date" name="date" id="date" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                        <div>
                            <label for="amount">Amount (RM)</label>
                            <input type="text" name="amount" id="amount" placeholder="XXX">
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="reset" class="cancel-btn">Cancel</button>
                        <button type="button" class="history-btn">
                            <a href="{{ route('budget.history') }}" class="btn btn-primary">History</a>
                        </button>
                        <button type="submit" class="save-btn">Submit</button>
                    </div>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <style>
                /* General styling for the form */
                .form-container {
                    background-color: rgba(255, 255, 255, 0.9); /* Slight transparency */
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    max-width: 500px;
                    width: 100%;
                }

                .form-container input[type="text"],
                .form-container textarea {
                    width: 100%;
                    padding: 10px;
                    margin: 5px 0 15px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                }

                .form-container textarea {
                    resize: none;
                    height: 80px;
                }

                .form-container label {
                    font-weight: bold;
                    margin-bottom: 5px;
                    display: block;
                }

                .form-row {
                    display: flex;
                    justify-content: space-between;
                    gap: 10px; /* Added gap for spacing */
                }

                .button-container {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                }

                .button-container button {
                    padding: 10px 15px;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    color: #fff;
                    width: 30%;
                }

                .cancel-btn { background-color: #d9534f; }
                .history-btn { background-color: #5bc0de; }
                .save-btn { background-color: #5cb85c; }

                .button-container button:hover { opacity: 0.7; }

                /*date column*/
                .date-container {
                    margin-top: 10px; /* Moves the arrangement downward */
                }

                input[type="date"] {
                    border-radius: 10px; /* Rounds the corners of the input box */
                    padding: 10px 12px; /* Adds padding inside the input box */
                    font-size: 16px; /* Adjust font size for better readability */
                    border: 1px solid #ccc; /* Light border around the input */
                    transition: border-color 0.3s ease; /* Smooth transition for border color change */
                }

                input[type="date"]:focus {
                    border-color: #4A90E2; /* Changes border color on focus */
                    outline: none; /* Removes the default outline on focus */
                }

                /*time column*/
                .time-container {
                    margin-top: 10px; /* Moves the arrangement downward */
                }

                input[type="time"] {
                    border-radius: 10px; /* Rounds the corners of the input box */
                    padding: 10px 12px; /* Adds padding inside the input box */
                    font-size: 16px; /* Adjust font size for better readability */
                    border: 1px solid #ccc; /* Light border around the input */
                    transition: border-color 0.3s ease; /* Smooth transition for border color change */
                }

                input[type="time"]:focus {
                    border-color: #4A90E2; /* Changes border color on focus */
                    outline: none; /* Removes the default outline on focus */
                }
            </style>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>
