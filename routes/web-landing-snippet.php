<?php
// Tambahkan baris ini ke routes/web.php (di luar prefix role manapun,
// karena landing page adalah halaman publik tanpa role):

Route::view('/', 'landing.index')->name('landing');
