<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// echo 'TimeStamp: ' . strtotime('first day of', 1496250000);
// echo '<br>';
// echo 'Date: ' . date('Y-m-d H:i:s', strtotime('first day of', 1496250000));
// echo '<br>';


// echo 'TimeStamp: ' . strtotime('first day of', mktime(0, 0, 0, 6, 1, 2017));
// echo '<br>';
// echo 'Date: ' . date('Y-m-d H:i:s', strtotime('first day of', mktime(0, 0, 0, 6, 1, 2017)));
// echo '<br>';
// echo '<pre>';
//var_dump(getdate(strtotime('-1 months', mktime(0, 0, 0, 9, 1, 2017))));
//echo cal_days_in_month(CAL_GREGORIAN, 6, 2017);
// var_dump(getdate(strtotime('now')));
// print_r($nama_hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'));
//die();

//echo $x = 2;
// echo '<br>';
// echo $x++;
// echo '<br>';
// echo ++$x;
// die();



class Kalender {

	private $bulan;
	private $tahun;
	private $nama_hari;
	private $jumlah_hari;
	private $info_tanggal_pertama;
	private $jumlah_hari_bulan_sebelumnya;

	private $posisi_tanggal;

	public function __construct($bulan, $tahun, $nama_hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')) {
		// Pengaturan minimal yang dibutuhkan untuk membangun kalender

		// Nama-nama hari dalam seminggu
		$this->nama_hari = $nama_hari;

		/*
		Menganalisa Kalender
		*/
		// Nama bulan pada Kalender
		$this->bulan = $bulan;
		// Tahun pada Kalender
		$this->tahun = $tahun;
		
		// Jumlah hari pada Kalender
		$this->jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);
		// Jumlah hari bulan sebelumnya
		$this->jumlah_hari_bulan_sebelumnya = cal_days_in_month(CAL_GREGORIAN, $this->bulan - 1, $this->tahun);


		// Informasi Tanggal Pertama pada Kalender
		$this->info_tanggal_pertama = getdate(strtotime('first day of', mktime(0, 0, 0, $this->bulan, 1, $this->tahun)));

		// Setting Posisi Tanggal sama dengan Posisi Tanggal Pertama Kalender
		$this->posisi_tanggal = $this->info_tanggal_pertama['wday'];
	}

	public function show() {

		// Membangun Tabel Kalender
		// Membuat Caption untuk keterangan Bulan dan Tahun Tabel Kalender
		$output  = '<table>';
		$output .= '<caption>'. $this->info_tanggal_pertama['month'] .' '. $this->tahun .'</caption>';

		// Membuat Baris & Kolom Nama Hari pada Tabel Kalender
		$output .= '<tr>';

		foreach($this->nama_hari as $hari) {
			$output .= '<th>'. $hari .'</th>';
		}

		// Tag penutup </tr> untuk menutup Baris & Kolom Nama Hari
		// Tag pembuka <tr> untuk mulai membuat Baris & Kolom Tanggal
		$output .= '</tr><tr>';

		// Posisi Tanggal:  [0] => Minggu [1] => Senin [2] => Selasa [3] => Rabu [4] => Kamis [5] => Jumat [6] => Sabtu

		// Jika Posisi Tanggal tidak jatuh pada hari Minggu Pertama, 
		// maka kita menyertakan tanggal pada bulan sebelumnya
		if ($this->posisi_tanggal > 0) {
			$tanggal_bulan_sebelumnya = $this->jumlah_hari_bulan_sebelumnya - $this->posisi_tanggal;
			for ($x = 1; $x <= $this->posisi_tanggal; $x++) {
				$output .= '<td class="tanggal_bulan_sebelumnya">'. ++$tanggal_bulan_sebelumnya .'</td>';
			}
		}

		// Tanggal sebagai Kontrol Perulangan; setting mulai dari tanggal 1
		$tanggal = 1;

		// Perulangan tanggal sebanyak jumlah hari dalam satu bulan pada Kalender
		while ($tanggal <= $this->jumlah_hari) {

			// Cek posisi tanggal pada saat perulangan

			// Bila posisi tanggal = 7 maka kita perlu membuat Baris Baru Tanggal pada Tabel Kalender.
			if ($this->posisi_tanggal == 7) {
				// Reset Posisi Tanggal menjadi 0 untuk membuat Baris Baru Tanggal
				$this->posisi_tanggal = 0;
				$output .= '</tr><tr>';
			}

			// Membuat pertanda pada Hari Minggu pada Kalender
			$tanggal_saat_ini = ($this->bulan == (int) date('n') && $this->tahun == (int) date('Y') && $tanggal == (int) date('j')) ? ' tanggal_saat_ini' : '';
			// Membuat pertanda untuk tanggal saat ini pada kalender
			$libur = getdate(mktime(0, 0, 0, $this->bulan, $tanggal, $this->tahun))['weekday'] === 'Sunday' ? ' libur' :  '';

			// Membuat Kolom Tanggal
			$output .= '<td class="'.$libur.$tanggal_saat_ini.'">'.$tanggal.'</td>';

			// Tambahkan nilai 1 pada Tanggal untuk Kontrol Perulangan
			$tanggal++;
			// Tambahkan nilai 1 pada Posisi Tanggal untuk Kontrol Baris Baru Tanggal
			$this->posisi_tanggal++;
		}

		// Ketika perulangan berhenti,
		// bila Posisi Tanggal pada Minggu Terakhir tidak sama dengan 7
		// maka kita sertakan tanggal pada bulan berikutnya
		if ($this->posisi_tanggal != 7) {
			$tanggal_bulan_berikutnya = 7 - $this->posisi_tanggal;
			for($x = 1; $x <= $tanggal_bulan_berikutnya; $x++) {
				$output .= '<td class="tanggal_bulan_berikutnya">'. $x .'</td>';
			}
		}

		// Menutup Baris dan Tabel Kalender
		$output .= '</tr>';
		$output  .= '</table>';

		echo $output;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>KALENDER PHP</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<!-- 
	Gantikan $bulan dimulai dari angka 1 sampai angka 12 (Januari s/d Desember)
	Gantikan $tahun dengan format: 2017
	
	contoh: $kalender = new Kalender(2, 2016);
			$kalender->show();
	$kalender = new Kalender(int $bulan, int $tahun)
	 -->
	<?php
		$bulan = date('n');
		$tahun = date('Y');
		$kalender = new Kalender($bulan, $tahun);
		$kalender->show();
	?>
</body>
</html>