/**
 * ====================================================================
 * FRONTEND HELPER CONTOH (Untuk React / Vue / Vite / Next.js)
 * ====================================================================
 * File ini sebagai referensi implementasi frontend terpisah:
 * 1. Membaca base URL dari environment variable (VITE_API_URL / NEXT_PUBLIC_API_URL).
 * 2. Membentuk URL publik untuk preview/download lampiran:
 *    - file_surat
 *    - file_grn
 *    - berita_acara
 */

// 1. Ambil URL Backend dari Environment Variables (Mendukung Vite & Next.js)
export const getApiBaseUrl = () => {
    if (typeof import.meta !== 'undefined' && import.meta.env?.VITE_API_URL) {
        return import.meta.env.VITE_API_URL.replace(/\/+$/, '');
    }
    if (typeof process !== 'undefined' && process.env?.NEXT_PUBLIC_API_URL) {
        return process.env.NEXT_PUBLIC_API_URL.replace(/\/+$/, '');
    }
    return 'http://localhost:8080';
};

export const API_BASE_URL = getApiBaseUrl();

/**
 * 2. Helper URL Lampiran (Preview & Download)
 * @param {'surat' | 'grn' | 'berita_acara'} type - Jenis folder lampiran
 * @param {string | null} filename - Nama file yang tersimpan di database
 * @returns {string | null} URL lengkap ke backend publik
 * 
 * Contoh Penggunaan:
 *   const suratUrl = getAttachmentUrl('surat', item.file_surat);
 *   const grnUrl = getAttachmentUrl('grn', item.file_grn);
 *   const beritaUrl = getAttachmentUrl('berita_acara', item.berita_acara);
 */
export const getAttachmentUrl = (type, filename) => {
    if (!filename) return null;
    return `${API_BASE_URL}/uploads/${type}/${encodeURIComponent(filename)}`;
};

/**
 * 3. Contoh Fetch Data GRN dari REST API Backend
 */
export const fetchGrnList = async (params = {}) => {
    const query = new URLSearchParams(params).toString();
    const res = await fetch(`${API_BASE_URL}/api/grn?${query}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'include', // Jika backend menggunakan cookie session
    });
    return await res.json();
};

/**
 * 4. Contoh Submit GRN dengan Upload Lampiran (Multipart Form Data)
 */
export const submitGrn = async (formData) => {
    const res = await fetch(`${API_BASE_URL}/api/grn/submit`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'include',
        body: formData,
    });
    return await res.json();
};

