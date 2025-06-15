public function run()
{
    \App\Models\Kategori::create(['nama' => 'Politik']);
    \App\Models\Kategori::create(['nama' => 'Ekonomi']);
    \App\Models\Kategori::create(['nama' => 'Olahraga']);
}