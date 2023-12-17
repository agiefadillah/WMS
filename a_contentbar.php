<div class="page-breadcrumb">
    <div class="row">
        <!-- Tanggal -->
        <div class="col-7 align-self-center">
            <h5 class="page-title text-truncate text-dark font-weight-medium mb-1">
                <script type='text/javascript'>
                    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var thisDay = date.getDay(),
                        thisDay = myDays[thisDay];
                    var yy = date.getYear();
                    var year = (yy < 1000) ? yy + 1900 : yy;
                    document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                </script>
            </h5>

            <!-- Jam -->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item">
                            <h6 id="clock-bikinan">
                                <script type='text/javascript'>
                                    // Fungsi untuk menampilkan waktu dalam format HH:MM:SS
                                    function displayTime() {
                                        var date = new Date();
                                        var hours = date.getHours();
                                        var minutes = date.getMinutes();
                                        var seconds = date.getSeconds();

                                        // Tambahkan angka 0 di depan angka jam, menit, dan detik jika nilainya kurang dari 10
                                        if (hours < 10) {
                                            hours = '0' + hours;
                                        }
                                        if (minutes < 10) {
                                            minutes = '0' + minutes;
                                        }
                                        if (seconds < 10) {
                                            seconds = '0' + seconds;
                                        }

                                        // Tampilkan waktu dalam elemen dengan ID 'current-time'
                                        document.getElementById('clock-bikinan').innerText = hours + ':' + minutes + ':' + seconds;
                                    }

                                    // Panggil fungsi displayTime() untuk pertama kali agar waktu langsung ditampilkan saat halaman dimuat
                                    displayTime();

                                    // Gunakan setInterval untuk memperbarui waktu setiap detik (1000 ms)
                                    setInterval(displayTime, 1000);
                                </script>
                            </h6>

                        </li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>
</div>