<?php

namespace Database\Seeders;

use App\Domain\Entities\Prompt;
use Illuminate\Database\Seeder;

class PromptSeeder extends Seeder
{
    public function run(): void
    {
        // TL;DR V1 - Optimized prompt for shorts
        Prompt::create([
            'name' => 'tldr_v1',
            'description' => 'Optimized TL;DR prompt for YouTube Shorts with voice-over friendly flow',
            'system_prompt' => 'Kamu adalah AI penulis script konten TL;DR untuk YouTube Shorts channel "Intinya Gini".',
            'user_prompt_template' => $this->getTLDRV1Template(),
            'config' => [
                'temperature' => 0.7,
                'max_tokens' => 400,
            ],
            'is_active' => true,
            'version' => 1,
        ]);

        // Drama/Gossip specialized prompt
        Prompt::create([
            'name' => 'tldr_drama',
            'description' => 'Specialized for gossip/drama - extra neutral, fact-focused',
            'system_prompt' => 'Kamu adalah AI penulis konten yang netral dan fact-based untuk topik gosip/drama.',
            'user_prompt_template' => $this->getDramaTemplate(),
            'config' => [
                'temperature' => 0.6, // Lower temp for more neutral
                'max_tokens' => 400,
            ],
            'is_active' => true,
            'version' => 1,
        ]);

        // Tech specialized prompt  
        Prompt::create([
            'name' => 'tldr_tech',
            'description' => 'Specialized for technology topics - explain clearly',
            'system_prompt' => 'Kamu adalah AI penulis tech content yang bisa explain complex topics simply.',
            'user_prompt_template' => $this->getTechTemplate(),
            'config' => [
                'temperature' => 0.7,
                'max_tokens' => 400,
            ],
            'is_active' => true,
            'version' => 1,
        ]);
    }

    private function getTLDRV1Template(): string
    {
        return <<<'PROMPT'
BRAND IDENTITY:
Tagline WAJIB: "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."
Gaya: Santai, gen-Z, langsung ke inti, no bullshit.

ATURAN WAJIB:
1. SELALU mulai script dengan tagline di atas
2. Hook pakai konflik + pertanyaan
3. Flow kalimat enak dibacain (voice-over friendly)
4. DILARANG analogi aneh atau bertele-tele
5. Untuk isu sensitif: NETRAL, fokus fakta
6. Pakai "lu/gue" bukan "kamu/saya"

STRUKTUR HOOK:
- Pertanyaan ganda atau konflik
- Maksimal 15 kata
- Contoh: "Rizky Nabila pelakor? Bukti beneran ada… atau cuma omongan viral?"

STRUKTUR SCRIPT (~100 kata):
1. Tagline channel (WAJIB!)
2. Konteks singkat
3. Situasi saat ini
4. Kenapa viral/penting
5. Takeaway

VOICE-OVER TIPS:
- Gunakan koma untuk jeda
- Hindari kalimat >20 kata
- Pakai "..." untuk suspense

---

Topik: {{title}}
Durasi: {{duration}} detik

Output JSON:
{
    "hook": "Pertanyaan konflik, max 15 kata",
    "content": "Script WAJIB mulai dengan tagline, ~100 kata",
    "key_points": ["Poin 1", "Poin 2", "Poin 3"],
    "title": "Judul catchy, max 8 kata",
    "caption": "Caption dengan #IntinyaGini dan hashtag relevan"
}

Output HANYA JSON.
PROMPT;
    }

    private function getDramaTemplate(): string
    {
        return <<<'PROMPT'
BRAND: "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."

FOKUS DRAMA/GOSSIP:
1. WAJIB netral - jangan judge
2. Fokus fakta vs opini
3. Highlight kurangnya bukti jika applicable
4. Edukasi tentang verifikasi
5. Hindari sensasionalisasi

TONE: Santai tapi responsible journalist

Topik: {{title}}

Output JSON dengan struktur sama seperti biasa.
PROMPT;
    }

    private function getTechTemplate(): string
    {
        return <<<'PROMPT'
BRAND: "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."

FOKUS TEKNOLOGI:
1. Explain simply tanpa jargon ribet
2. Highlight kenapa penting untuk user
3. Kasih analogi relatable jika perlu
4. Fokus dampak praktis

TONE: Tech-savvy tapi accessible

Topik: {{title}}

Output JSON dengan struktur sama seperti biasa.
PROMPT;
    }
}
