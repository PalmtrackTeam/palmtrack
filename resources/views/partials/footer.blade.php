<footer class="bg-gray-100 border-t border-gray-300 pt-16 pb-6">

    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-12">

        <!-- LOGO + DESKRIPSI (DIBUAT TENGAH) -->
        <div class="text-center flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="w-32 mb-6 opacity-90">

            <p class="text-gray-700 text-sm leading-relaxed max-w-[220px]">
                PT Irfan Sawit Jaya<br>
                Perusahaan Pengelolaan & Produksi Kelapa Sawit.
            </p>
        </div>

        <!-- OUR OFFICE -->
        <div>
            <h3 class="font-semibold tracking-wider text-gray-900 mb-4">OUR OFFICE</h3>

            <p class="text-gray-700 text-sm leading-relaxed mb-4">
                PT. Irfan Sawit Jaya<br>
                Jl. Lintas Sumatera No. 12<br>
                Kecamatan Rantau Prapat, Sumatera Utara
            </p>

            <p class="flex items-center gap-2 text-gray-700 text-sm">
                <span>📧</span> info@ptirfansawitjaya.co.id
            </p>

            <p class="flex items-center gap-2 text-gray-700 text-sm mt-2">
                <span>📞</span> (061) 123-4567
            </p>
        </div>

        <!-- PETA SITUS + LOKASI -->
        <div>
            <h3 class="font-semibold tracking-wider text-gray-900 mb-4">PETA SITUS</h3>

            <ul class="space-y-2 text-gray-600 text-sm mb-6">
                <li><a href="/kontak" class="hover:text-green-700">Kontak</a></li>
                <li><a href="/tentang" class="hover:text-green-700">Perusahaan Kami</a></li>
                
            </ul>

            <h4 class="text-green-700 font-semibold mb-3">Lokasi Kami</h4>

            <!-- MAP DITARO DI BAWAH, DIPERKECIL -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7979.698767495059!2d99.8283!3d2.1024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x302e0519b33e0e3d%3A0x8f62e0a83e6bce1a!2sRantau%20Prapat!5e0!3m2!1sid!2sid!4v1700000000000"
                width="100%"
                height="160"
                class="rounded-lg shadow-md"
                style="border:0;"
                allowfullscreen
                loading="lazy">
            </iframe>
        </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="mt-12 border-t border-gray-300 pt-6 text-center text-gray-700 text-sm">
        © 2025 PT Irfan Sawit Jaya. Semua hak dilindungi.
    </div>

</footer>
