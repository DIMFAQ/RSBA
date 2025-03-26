<div>
    <style>
        .bold {
            font-weight: bold;
        }
    </style>

    <div align="center">
        <img src="" style="height:40px;">
    </div>
    <table cellpadding="3" align="center" style="font-size:10px; ">
        <tr>
            <td align="center" colspan="6" style="font-size:16px;text-transform: uppercase;"><b><?= $rs->nama ?></b></td>
        </tr>
        <tr>
            <td align="center" colspan="6">SURAT PERMINTAAN PROSES PEMBAYARAN<br>(Kontrak, Sundries, Material, dll.)<br><br></b></td>
        </tr>
        <tr>
            <td style="width:200px;" class="bold">Kepada</td>
            <td class="bold">:</td>
            <td>Wadir Keuangan</td>
            <td class="bold">Nomor</td>
            <td class="bold">:</td>
            <td><?= $suratSp3->no ?></td>
            {{-- style="<?= $data->hapus == 1 ? 'color:red; text-decoration: line-through;' : '' ?>" --}}
        </tr>
        <tr>
            <td><b>Dari</b></td>
            <td><b>:</b></td>
            <td><?= '' ?></td>
            <td><b>Tgl</b></td>
            <td><b>:</b></td>
            <td><?= '' ?></td>
        </tr>
        <tr>
            <td colspan="6" style="border-top:1px solid;"></td>
        </tr>
        <tr>
            <td colspan="6"><b>Terlampir dikirimkan dokumen pendukung pembayaran atas (Kontrak, Sundries, Material, dll) sbb:</b><br></td>
        </tr>
        <tr>
            <td><b>Keterangan Pembayaran</b></td>
            <td><b>:</b></td>
            <td colspan="4"><?= '' ?></td>
        </tr>
        <tr>
            <td><b>Nama Rekanan / Pelaksana</b></td>
            <td><b>:</b></td>
            <td colspan="4"><?= '' ?></td>
        </tr>
        <tr>
            <td valign="top"><b>Jumlah Pembayaran</b></td>
            <td valign="top"><b>:</b></td>
            <td colspan="4">
                <table cellpadding="5" border="1" style='border-collapse:collapse;font-size:12px;'>

                </table>
            </td>
        </tr>
        <tr>
            <td><b>Terbilang</b></td>
            <td><b>:</b></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td><b>Cara Pembayaran</b></td>
            <td><b>:</b></td>
            <td colspan="4">

            </td>
        </tr>
        <tr>
            <td colspan="6"><b>Demikian untuk diterima dengan baik dan pelaksanaan pembuatan bukti kas / bank untuk proses pembayaran selanjutnya.<br>Atas perhatian dan kerjasamanya diucapkan
                    terima kasih.</b></td>
        </tr>
        <tr>
            <td colspan="6" align="right">
                <table style="font-size:12px;">
                    <tr>
                        <td>Disetujui Oleh,</td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b><br><br><br> </b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <table style="font-size:8px;">
                    <tr>
                        <td>Tembusan:</td>
                    </tr>
                    <tr>
                        <td>1. Direktur</td>
                    </tr>
                    <tr>
                        <td>2. Arsip</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
