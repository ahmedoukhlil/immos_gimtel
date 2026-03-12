<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étiquettes groupées par emplacement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6" x-data="etiquettesTousEmplacements()" x-cloak>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('biens.index') }}" class="hover:text-indigo-600 transition-colors">Immobilisations</a>
                    <span>/</span>
                    <span class="text-gray-700 font-medium">Impression groupée</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Toutes les étiquettes par emplacement</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ count($emplacementsData) }} emplacement(s) • {{ collect($emplacementsData)->sum(fn($e) => count($e['biens'])) }} bien(s)
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('biens.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Retour
                </a>
                <button x-show="!generated" @click="generate()" :disabled="loading" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                    <span x-text="loading ? 'Génération…' : 'Générer le PDF'"></span>
                </button>
                <template x-if="generated">
                    <div class="flex items-center gap-2">
                        <button @click="download()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Télécharger
                        </button>
                        <button @click="print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Imprimer
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="statusText" class="rounded-lg border px-4 py-3 text-sm"
            :class="{
                'bg-blue-50 border-blue-200 text-blue-700': statusType === 'info',
                'bg-yellow-50 border-yellow-200 text-yellow-700': statusType === 'loading',
                'bg-green-50 border-green-200 text-green-700': statusType === 'success',
                'bg-red-50 border-red-200 text-red-700': statusType === 'error'
            }">
            <span x-text="statusText"></span>
        </div>

        <div x-show="loading" class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progression</span>
                <span class="text-sm text-gray-500" x-text="progressCurrent + ' / ' + progressTotal"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-200" :style="'width: ' + progressPercent + '%'"></div>
            </div>
        </div>

        <div x-show="generated" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <iframe id="pdfContainer" class="w-full border-0" style="height: 70vh; min-height: 500px;"></iframe>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

    <script>
        function etiquettesTousEmplacements() {
            return {
                emplacementsData: @json($emplacementsData),
                qrDataUris: @json($qrDataUris),
                loading: false,
                generated: false,
                statusType: 'info',
                statusText: 'Prêt à générer le fichier groupé.',
                progressCurrent: 0,
                progressTotal: 0,
                progressPercent: 0,
                pdfBlobUrl: null,

                MM: 2.83465,
                A4_W: 595.28,
                A4_H: 841.89,
                LABEL_W_MM: 70,
                LABEL_H_MM: 24.4,
                COLS: 3,
                MARGIN_TOP_MM: 7,
                MARGIN_BOTTOM_MM: 7,

                get LABEL_W() { return this.LABEL_W_MM * this.MM; },
                get LABEL_H() { return this.LABEL_H_MM * this.MM; },
                get MARGIN_LEFT() { return (this.A4_W - this.COLS * this.LABEL_W) / 2; },
                get MARGIN_TOP() { return this.MARGIN_TOP_MM * this.MM; },
                get MARGIN_BOTTOM() { return this.MARGIN_BOTTOM_MM * this.MM; },
                get AVAILABLE_H() { return this.A4_H - this.MARGIN_TOP - this.MARGIN_BOTTOM; },
                get ROWS() { return Math.floor(this.AVAILABLE_H / this.LABEL_H); },
                get TOTAL() { return this.COLS * this.ROWS; },
                get ROW_GAP() {
                    const usedH = this.ROWS * this.LABEL_H;
                    const remaining = this.AVAILABLE_H - usedH;
                    return this.ROWS > 1 ? remaining / (this.ROWS - 1) : 0;
                },
                get ROW_PITCH() { return this.LABEL_H + this.ROW_GAP; },

                async dataUriToEmbeddedPng(pdfDoc, dataUri, size = 360) {
                    return await new Promise((resolve, reject) => {
                        const img = new Image();
                        img.onload = async () => {
                            const c = document.createElement('canvas');
                            c.width = size;
                            c.height = size;
                            const ctx = c.getContext('2d');
                            ctx.fillStyle = '#fff';
                            ctx.fillRect(0, 0, size, size);
                            ctx.drawImage(img, 0, 0, size, size);
                            try {
                                const embedded = await pdfDoc.embedPng(c.toDataURL('image/png'));
                                resolve(embedded);
                            } catch (e) {
                                reject(e);
                            }
                        };
                        img.onerror = () => reject(new Error('QR bien non lisible'));
                        img.src = dataUri;
                    });
                },

                buildSlots() {
                    const slots = [];
                    this.emplacementsData.forEach((emp) => {
                        slots.push({
                            type: 'qr',
                            emplacementId: emp.idEmplacement,
                            emplacementName: emp.Emplacement || `Emplacement ${emp.idEmplacement}`,
                            qrDataUri: this.qrDataUris[String(emp.idEmplacement)] || null,
                        });

                        (emp.biens || []).forEach((b) => {
                            slots.push({
                                type: 'bien',
                                barcode_value: String(b.barcode_value || b.NumOrdre || '').trim(),
                                code_formate: String(b.code_formate || '').trim(),
                                designation: String(b.designation || '').trim(),
                                qr_data_uri: String(b.qr_data_uri || '').trim(),
                            });
                        });
                    });

                    return slots;
                },

                async generate() {
                    this.loading = true;
                    this.generated = false;
                    this.progressCurrent = 0;
                    this.progressPercent = 0;
                    this.statusType = 'loading';
                    this.statusText = 'Génération du PDF groupé en cours…';

                    try {
                        const slots = this.buildSlots();
                        this.progressTotal = slots.length;

                        if (slots.length === 0) {
                            throw new Error('Aucune étiquette à générer.');
                        }

                        const { PDFDocument, StandardFonts, rgb } = PDFLib;
                        const pdfDoc = await PDFDocument.create();
                        const font = await pdfDoc.embedFont(StandardFonts.Helvetica);
                        const fontBold = await pdfDoc.embedFont(StandardFonts.HelveticaBold);

                        const mm = this.MM;
                        // QR agrandi tout en conservant l'étiquette 70mm x 24.4mm
                        const BC_TOP_OFFSET = 2.5 * mm;
                        const BC_SIZE = 11.0 * mm;
                        const CODE_Y_OFFSET = 16.5 * mm;
                        const DESIG_Y_OFFSET = 20.0 * mm;
                        const FS_CODE = 7;
                        const FS_DESIG = 5;

                        const totalPages = Math.ceil(slots.length / this.TOTAL);
                        let slotIndex = 0;

                        for (let pi = 0; pi < totalPages; pi++) {
                            const page = pdfDoc.addPage([this.A4_W, this.A4_H]);
                            const slotStart = pi * this.TOTAL;
                            const slotEnd = Math.min(slotStart + this.TOTAL, slots.length);

                            for (let slot = slotStart; slot < slotEnd; slot++) {
                                const item = slots[slot];
                                const posOnPage = slot - slotStart;
                                const col = posOnPage % this.COLS;
                                const row = Math.floor(posOnPage / this.COLS);

                                const labelX = this.MARGIN_LEFT + col * this.LABEL_W;
                                const labelTopY = this.A4_H - this.MARGIN_TOP - row * this.ROW_PITCH;

                                if (item.type === 'qr') {
                                    if (item.qrDataUri) {
                                        const img = await new Promise((resolve, reject) => {
                                            const image = new Image();
                                            image.onload = async () => {
                                                const c = document.createElement('canvas');
                                                c.width = 360;
                                                c.height = 360;
                                                const ctx = c.getContext('2d');
                                                ctx.fillStyle = '#fff';
                                                ctx.fillRect(0, 0, 300, 300);
                                                ctx.drawImage(image, 0, 0, 360, 360);
                                                try {
                                                    const embedded = await pdfDoc.embedPng(c.toDataURL('image/png'));
                                                    resolve(embedded);
                                                } catch (e) {
                                                    reject(e);
                                                }
                                            };
                                            image.onerror = () => reject(new Error('QR emplacement non lisible'));
                                            image.src = item.qrDataUri;
                                        });

                                        const qrSize = BC_SIZE;
                                        const qrX = labelX + (this.LABEL_W - qrSize) / 2;
                                        const qrY = labelTopY - BC_TOP_OFFSET - qrSize;
                                        page.drawImage(img, { x: qrX, y: qrY, width: qrSize, height: qrSize });
                                    }

                                    const empText = `EMP-${item.emplacementId}`;
                                    const empTw = font.widthOfTextAtSize(empText, FS_CODE);
                                    page.drawText(empText, {
                                        x: labelX + (this.LABEL_W - empTw) / 2,
                                        y: labelTopY - CODE_Y_OFFSET,
                                        size: FS_CODE,
                                        font,
                                        color: rgb(0, 0, 0),
                                    });

                                    let name = item.emplacementName;
                                    const maxW = this.LABEL_W * 0.92;
                                    while (fontBold.widthOfTextAtSize(name, FS_DESIG) > maxW && name.length > 1) {
                                        name = name.slice(0, -1);
                                    }
                                    if (name.length < item.emplacementName.length) name += '…';
                                    const nameTw = fontBold.widthOfTextAtSize(name, FS_DESIG);
                                    page.drawText(name, {
                                        x: labelX + (this.LABEL_W - nameTw) / 2,
                                        y: labelTopY - DESIG_Y_OFFSET,
                                        size: FS_DESIG,
                                        font: fontBold,
                                        color: rgb(0.2, 0.2, 0.2),
                                    });
                                } else {
                                    if (item.qr_data_uri) {
                                        const img = await this.dataUriToEmbeddedPng(pdfDoc, item.qr_data_uri, 320);
                                        const qrY = labelTopY - BC_TOP_OFFSET - BC_SIZE;
                                        const qrX = labelX + (this.LABEL_W - BC_SIZE) / 2;
                                        page.drawImage(img, { x: qrX, y: qrY, width: BC_SIZE, height: BC_SIZE });
                                    }

                                    if (item.code_formate) {
                                        const tw = font.widthOfTextAtSize(item.code_formate, FS_CODE);
                                        page.drawText(item.code_formate, {
                                            x: labelX + (this.LABEL_W - tw) / 2,
                                            y: labelTopY - CODE_Y_OFFSET,
                                            size: FS_CODE,
                                            font,
                                            color: rgb(0, 0, 0),
                                        });
                                    }

                                    if (item.designation) {
                                        const maxTxtW = this.LABEL_W * 0.92;
                                        let txt = item.designation;
                                        while (font.widthOfTextAtSize(txt, FS_DESIG) > maxTxtW && txt.length > 1) {
                                            txt = txt.slice(0, -1);
                                        }
                                        if (txt.length < item.designation.length) txt += '…';
                                        const tw = font.widthOfTextAtSize(txt, FS_DESIG);
                                        page.drawText(txt, {
                                            x: labelX + (this.LABEL_W - tw) / 2,
                                            y: labelTopY - DESIG_Y_OFFSET,
                                            size: FS_DESIG,
                                            font,
                                            color: rgb(0, 0, 0),
                                        });
                                    }
                                }

                                slotIndex++;
                                this.progressCurrent = slotIndex;
                                this.progressPercent = Math.round((slotIndex / this.progressTotal) * 100);
                            }
                        }

                        const bytes = await pdfDoc.save();
                        if (this.pdfBlobUrl) URL.revokeObjectURL(this.pdfBlobUrl);
                        this.pdfBlobUrl = URL.createObjectURL(new Blob([bytes], { type: 'application/pdf' }));
                        document.getElementById('pdfContainer').src = this.pdfBlobUrl;

                        this.loading = false;
                        this.generated = true;
                        this.statusType = 'success';
                        this.statusText = `PDF généré — ${totalPages} page(s), ${slots.length} étiquette(s).`;
                    } catch (err) {
                        console.error(err);
                        this.loading = false;
                        this.statusType = 'error';
                        this.statusText = 'Erreur : ' + err.message;
                    }
                },

                download() {
                    if (!this.pdfBlobUrl) return;
                    const a = document.createElement('a');
                    a.href = this.pdfBlobUrl;
                    a.download = 'etiquettes_tous_emplacements.pdf';
                    a.click();
                },

                print() {
                    const frame = document.getElementById('pdfContainer');
                    if (frame && frame.contentWindow) {
                        try {
                            frame.contentWindow.print();
                        } catch {
                            const w = window.open(this.pdfBlobUrl, '_blank');
                            if (w) w.onload = () => w.print();
                        }
                    }
                }
            };
        }
    </script>
</body>
</html>
