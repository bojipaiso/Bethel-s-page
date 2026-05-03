<?php
// includes/footer.php
?>
    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- School Info -->
                <div class="footer-column">
                    <h3>Bethel International School</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Pawing, Palo, Leyte, Philippines 6501</p>
                    <p><i class="fas fa-phone-alt"></i> 0917-173-0284</p>
                    <p><i class="fas fa-envelope"></i> secretary@bethel.edu.ph</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/BethelInternationalSchool" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" width="16" height="16">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/bethel.sc/" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" width="16" height="16">
                                <path d="M12 2c2.7 0 3 .1 4.1.2 3.2.2 4.8 1.8 5 5 .1 1.1.1 1.4.1 4.8s0 3.7-.1 4.8c-.2 3.2-1.8 4.8-5 5-1.1.1-1.4.1-4.1.1s-3 0-4.1-.1c-3.2-.2-4.8-1.8-5-5-.1-1.1-.1-1.4-.1-4.8s0-3.7.1-4.8c.2-3.2 1.8-4.8 5-5C9 2.1 9.3 2 12 2zm0 3.8c-3.4 0-6.2 2.8-6.2 6.2s2.8 6.2 6.2 6.2 6.2-2.8 6.2-6.2-2.8-6.2-6.2-6.2zm0 10.2c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4zm6.4-11.8c-.8 0-1.4.6-1.4 1.4s.6 1.4 1.4 1.4 1.4-.6 1.4-1.4-.6-1.4-1.4-1.4z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Resources - PDF Links -->
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="calendar.php"><i class="fas fa-calendar-alt"></i> Academic Calendar</a></li>
                        <li><a href="newsletter.php"><i class="fas fa-newspaper"></i> School Newsletter</a></li>
                    </ul>
                </div>

                <!-- Hours -->
                <div class="footer-column">
                    <h3>Hours</h3>
                    <div class="hours-item"><span>Mon-Fri:</span><span>8AM - 5PM</span></div>
                    <div class="hours-item"><span>Sat:</span><span>9AM - 12PM</span></div>
                    <div class="hours-item"><span>Sun:</span><span>Closed</span></div>
                    <div class="emergency-number">
                        <p>Emergency</p>
                        <a href="tel:+639171730284">0917-173-0284</a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2026 Bethel International School, Pawing, Palo, Leyte. All Rights Reserved.</p>
                <p style="font-size: 0.75rem; margin-top: 8px;">The Philippine Eagle symbolizes our commitment to strength, vision, and soaring excellence.</p>
            </div>
        </div>
    </footer>

    <a href="admin/login.php" class="admin-login-btn">🔧 Admin</a>

    <script>
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');
        if(mobileBtn && mainNav) {
            mobileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mainNav.classList.toggle('active');
                const icon = this.querySelector('i');
                if (mainNav.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
            document.querySelectorAll('#mainNav a').forEach(link => {
                link.addEventListener('click', function() {
                    mainNav.classList.remove('active');
                    const icon = mobileBtn.querySelector('i');
                    if(icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });
        }
    </script>
</body>
</html>