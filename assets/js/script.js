const { createApp, ref, computed, watch } = Vue;

// 1. LOGIKA UI
document.addEventListener('DOMContentLoaded', () => {
    // buat transisi halaman
    document.body.classList.add('page-transition');
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);

    // efek scroll di navbar
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // buat smooth scrolling
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname.split('/').pop();

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            
            if (href === currentPath || (currentPath === '' && href === 'beranda.php')) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// 2. LOGIKA HALAMAN ULASAN
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

// 3. LOGIKA HALAMAN GALERI
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

            watch(activeCategory, () => {
                currentPage.value = 1;
            });

            const paginatedGaleri = computed(() => {
                const start = (currentPage.value - 1) * itemsPerPage.value;
                return filteredGaleri.value.slice(start, start + itemsPerPage.value);
            });

            const totalPages = computed(() => {
                return Math.ceil(filteredGaleri.value.length / itemsPerPage.value);
            });

            return { categories, activeCategory, paginatedGaleri, currentPage, totalPages };
        }
    }).mount('#app-galeri');
}