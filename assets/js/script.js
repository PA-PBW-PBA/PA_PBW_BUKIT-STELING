const { createApp, ref, computed, watch } = Vue;

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('page-transition');
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);

    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname.split('/').pop();

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href === currentPath || (currentPath === '' && href === 'beranda.php')) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    }

    const validateField = (input, group, errorElement, validationFn, errorMsg) => {
        if (!input) return;
        input.addEventListener('input', function() {
            let val = this.value;
            val = val.replace(/\s{2,}/g, ' ');
            const emojiRegex = /[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F1E6}-\u{1F1FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/u;
            if (emojiRegex.test(val)) {
                val = val.replace(emojiRegex, '');
            }
            this.value = val;
            if (this.value === "") {
                group.classList.remove('is-invalid-custom', 'is-valid-custom');
                errorElement.style.display = 'none';
            } else if (!validationFn(this.value)) {
                group.classList.add('is-invalid-custom');
                group.classList.remove('is-valid-custom');
                errorElement.textContent = errorMsg;
                errorElement.style.display = 'block';
            } else {
                group.classList.remove('is-invalid-custom');
                group.classList.add('is-valid-custom');
                errorElement.style.display = 'none';
            }
        });
    };

    const emailInput = document.getElementById('email');
    if (emailInput) {
        const group = emailInput.closest('.input-group');
        const error = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'icloud.com', 'mail.com'];
        validateField(emailInput, group, error, (val) => {
            if (!emailRegex.test(val)) return false;
            const domain = val.split('@')[1].toLowerCase();
            return allowedDomains.includes(domain);
        }, "Gunakan email populer (Gmail, Yahoo, dll).");
    }

    const namaInput = document.getElementById('nama_lengkap');
    if (namaInput) {
        const group = namaInput.closest('.input-group');
        const error = document.getElementById('nama-error');
        validateField(namaInput, group, error, (val) => {
            return val.length >= 5 && /^[a-zA-Z\s]*$/.test(val);
        }, "Minimal 5 karakter & hanya boleh huruf.");
    }
});

if (document.getElementById('app-ulasan')) {
    createApp({
        setup() {
            const rating = ref(0);
            const hoverRating = ref(0);
            const komentar = ref('');
            const setRating = (n) => rating.value = n;
            const setHover = (n) => hoverRating.value = n;
            return { rating, hoverRating, komentar, setRating, setHover };
        }
    }).mount('#app-ulasan');
}

if (document.getElementById('app-galeri')) {
    createApp({
        setup() {
            const allPhotos = ref(window.dataGaleri || []);
            const categories = ref(['Semua', 'Pagi Hari', 'Sore & Sunset', 'Malam Hari']);
            const activeCategory = ref('Semua');
            const itemsPerPage = ref(6);
            const currentPage = ref(1);
            const filteredGaleri = computed(() => {
                if (activeCategory.value === 'Semua') return allPhotos.value;
                return allPhotos.value.filter(item => item.kategori === activeCategory.value);
            });
            watch(activeCategory, () => { currentPage.value = 1; });
            const paginatedGaleri = computed(() => {
                const start = (currentPage.value - 1) * itemsPerPage.value;
                return filteredGaleri.value.slice(start, start + itemsPerPage.value);
            });
            const totalPages = computed(() => Math.ceil(filteredGaleri.value.length / itemsPerPage.value));
            return { categories, activeCategory, paginatedGaleri, currentPage, totalPages };
        }
    }).mount('#app-galeri');
}