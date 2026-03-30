const { createApp, ref, computed } = Vue;

// LOGIKA HALAMAN ULASAN
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

// LOGIKA HALAMAN GALERI (REAKTIF & ANIMASI)
if (document.getElementById('app-galeri')) {
    createApp({
        setup() {
            // Data galeri diambil dari window object yang dikirim PHP
            const allPhotos = ref(window.dataGaleri || []);
            const categories = ref(['Semua', 'Pagi Hari', 'Sore & Sunset', 'Malam Hari']);
            const activeCategory = ref('Semua');

            const filteredGaleri = computed(() => {
                if (activeCategory.value === 'Semua') return allPhotos.value;
                return allPhotos.value.filter(item => item.kategori === activeCategory.value);
            });

            return { categories, activeCategory, filteredGaleri };
        }
    }).mount('#app-galeri');
}