import { chromium } from 'playwright';
import fs from 'fs';

const videoDir = '/Users/favian/.gemini/antigravity-ide/brain/8927a01d-65c4-42be-b870-196806da4a64';

if (!fs.existsSync(videoDir)) {
    fs.mkdirSync(videoDir, { recursive: true });
}

async function recordTour() {
    console.log('🎬 Memulai Rekaman Video Walkthrough Lengkap Kepegawaian RSBA...');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1280, height: 720 },
        recordVideo: {
            dir: videoDir,
            size: { width: 1280, height: 720 },
        },
    });

    const page = await context.newPage();

    // 1. LOGIN ADMIN
    console.log('1. Buka Login Page...');
    await page.goto('http://localhost:2023/', { waitUntil: 'load' });
    await page.waitForTimeout(1000);

    console.log('2. Input Email & Password (admin@rsba.com)...');
    const emailInput = page.locator('input[placeholder="Email"], input[type="email"]').first();
    const passInput = page.locator('input[placeholder="Password"], input[type="password"]').first();
    await emailInput.click();
    await emailInput.pressSequentially('admin@rsba.com', { delay: 40 });
    await passInput.click();
    await passInput.pressSequentially('1234', { delay: 40 });
    await page.keyboard.press('Enter');

    console.log('3. Menunggu Dashboard...');
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    await page.waitForTimeout(2500);
    await page.evaluate(() => window.scrollBy({ top: 300, behavior: 'smooth' }));
    await page.waitForTimeout(1500);

    // 2. JADWAL KERJA
    console.log('4. Buka Jadwal Kerja...');
    await page.goto('http://localhost:2023/kepegawaian/jadwal-kerja', { waitUntil: 'load' });
    await page.waitForTimeout(3000);
    await page.evaluate(() => window.scrollBy({ top: 300, behavior: 'smooth' }));
    await page.waitForTimeout(1500);

    // 3. KELOLA GRID JADWAL IGD
    console.log('5. Buka Grid Jadwal Shift IGD...');
    await page.goto('http://localhost:2023/kepegawaian/jadwal-kerja/kelola/1', { waitUntil: 'load' });
    await page.waitForTimeout(3000);
    await page.evaluate(() => window.scrollBy({ top: 350, behavior: 'smooth' }));
    await page.waitForTimeout(2000);

    // 4. KARYAWAN
    console.log('6. Buka Data Karyawan...');
    await page.goto('http://localhost:2023/kepegawaian/karyawan', { waitUntil: 'load' });
    await page.waitForTimeout(2500);
    await page.evaluate(() => window.scrollBy({ top: 250, behavior: 'smooth' }));
    await page.waitForTimeout(1500);

    // 5. CUTI
    console.log('7. Buka Pengajuan Cuti...');
    await page.goto('http://localhost:2023/kepegawaian/surat/cuti', { waitUntil: 'load' });
    await page.waitForTimeout(2500);

    // 6. GAJI
    console.log('8. Buka Penggajian & PPh 21 TER...');
    await page.goto('http://localhost:2023/kepegawaian/gaji', { waitUntil: 'load' });
    await page.waitForTimeout(2500);

    // 7. LAPORAN
    console.log('9. Buka Laporan Kepegawaian...');
    await page.goto('http://localhost:2023/kepegawaian/laporan', { waitUntil: 'load' });
    await page.waitForTimeout(2500);

    // 8. AKREDITASI
    console.log('10. Buka Modul Akreditasi...');
    await page.goto('http://localhost:2023/kepegawaian/akreditasi', { waitUntil: 'load' });
    await page.waitForTimeout(2500);

    // 9. QR PORTAL PUBLIK
    console.log('11. Buka Portal Publik Verifikasi QR...');
    await page.goto('http://localhost:2023/verifikasi-surat/RDjGYgBKllx1rVeU7Ql45NaK4sGTbn4GodX5A5aE', { waitUntil: 'load' });
    await page.waitForTimeout(3000);
    await page.evaluate(() => window.scrollBy({ top: 250, behavior: 'smooth' }));
    await page.waitForTimeout(1500);

    console.log('💾 Menyimpan rekaman video...');
    const videoFile = await page.video();
    await context.close();
    await browser.close();

    const videoPath = await videoFile.path();
    console.log('🎉 Video berhasil dibuat di:', videoPath);
    return videoPath;
}

recordTour().catch(err => {
    console.error('Error saat merekam video:', err);
    process.exit(1);
});
