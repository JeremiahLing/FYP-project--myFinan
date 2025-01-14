    <style>
        /* Social icons styling */
        .social-icons img {
            width: 20px;
            height: 20px;
            margin: 0 5px;
        }
    </style>
    
    <!--footer-->
    <footer class="py-5 text-center text-sm text-black dark:text-white/70">
        <div class="footer-container">
            <!-- Logo Section -->
            <div class="footer-logo">
                <img src="/favicon.ico" alt="myFinan Logo">
                <p class="text-left">A platform allows Small and Medium Enterprise Business Owners and Managers to manage finances.</p>
            </div>

            <!-- Quick Access Links -->
            <div class="footer-links">
                <h4>Quick Access</h4>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </div>

            <!-- Account Links -->
            <div class="footer-account">
                <h4>Create Account</h4>
                <ul>
                <li><a href="{{ route('register') }}">Register</a></li>
                <li><a href="{{ route('login') }}">Log In</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-contact">
                <h4>Contact Us</h4>
                <p><a href="tel:0161117777">016 - 111 7777</a></p>
                <p><a href="mailto:myFinan@gmail.com">myFinan@gmail.com</a></p>
                <div class="social-icons">
                    <a href="#"><img src="/facebook.ico" alt="Facebook"></a>
                    <a href="#"><img src="/instagram.ico" alt="Instagram"></a>
                    <a href="#"><img src="/whatsapp.ico" alt="WhatsApp"></a>
                </div>
            </div>
        </div>
    </footer>