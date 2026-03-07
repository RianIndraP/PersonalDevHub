<?php include_once 'includes/header.php';

$new_project = mysqli_fetch_assoc(mysqli_query($conn, "SELECT title FROM projects ORDER BY created_at DESC LIMIT 1"));
$project = mysqli_query($conn, "SELECT id, title, description FROM projects ORDER BY created_at DESC LIMIT 3");
$snippets = mysqli_query($conn, "SELECT id, title, code, explanation FROM snippets ORDER BY created_at DESC LIMIT 3");
$tech_stack = mysqli_query($conn, "SELECT * FROM tech_stacks ORDER BY id ASC");
?>


<!-- HERO -->
<section class="max-w-5xl mx-auto px-6 py-24 border-b border-border grid md:grid-cols-2 gap-12 items-center">
  <!-- Kolom Kiri: Teks -->
  <div class="hero-content">
    <p class="font-mono text-accent text-sm mb-4 tracking-widest uppercase">Hello, world —</p>
    <h1 class="text-5xl font-semibold tracking-tight leading-tight mb-4"><?= $admin['nama_lengkap']; ?></h1>
    <p class="text-dim text-lg font-mono mb-6">Web Developer</p>
    <p class="text-dim max-w-xl leading-relaxed text-base">
      I build clean, functional web applications. Focused on backend logic with PHP & MySQL, and crafting readable frontends with HTML, CSS, and JavaScript.
    </p>
    <div class="flex gap-3 mt-8">
      <a href="#projects" class="bg-accent text-bg font-mono text-sm font-semibold px-5 py-2 rounded hover:opacity-90 transition-opacity">View Projects</a>
      <a href="#snippets" class="border border-border font-mono text-sm px-5 py-2 rounded hover:border-dim transition-colors">Code Snippets</a>
    </div>
  </div>

  <div class="hero-visual hidden md:block relative">
    <div class="absolute -inset-4 bg-accent/5 blur-3xl rounded-full"></div>

    <div class="relative bg-zinc-950 border border-white/10 p-6 rounded-lg shadow-2xl font-mono text-[13px] leading-relaxed">
      <!-- Terminal Header -->
      <div class="flex gap-2 mb-5">
        <div class="w-3 h-3 rounded-full bg-white/10"></div>
        <div class="w-3 h-3 rounded-full bg-white/10"></div>
        <div class="w-3 h-3 rounded-full bg-white/10"></div>
      </div>

      <!-- Terminal Body -->
      <div class="space-y-1">
        <div>
          <span class="text-zinc-600 mr-2">$</span>
          <span class="text-accent">WhoAMi</span>
        </div>
        <div class="flex">
          <span class="text-zinc-600 mr-2">&gt;</span>
          <span class="text-zinc-100" id="typing-effect"></span>
        </div>

        <div class="pt-2">
          <span class="text-zinc-600 mr-2">$</span>
          <span class="text-accent">php</span> <span class="text-zinc-300">-v</span>
        </div>
        <div class="flex">
          <span class="text-zinc-600 mr-2">&gt;</span>
          <span class="text-zinc-400">PHP <?= phpversion(); ?> installed.</span>
        </div>

        <div class="flex">
          <span class="text-zinc-600 mr-2">&gt;</span>
          <span class="text-accent font-bold">[OK]</span>
          <span class="text-zinc-300 ml-2">Status: Active</span>
        </div>

        <div class="py-2 border-b border-white/5"></div> <!-- Divider -->

        <div>
          <span class="text-zinc-600 mr-2">$</span>
          <span class="text-zinc-300">Current Task?</span>
        </div>
        <div class="flex items-center text-zinc-100 italic">
          <span class="text-zinc-600 mr-2 border-l border-accent pl-2"></span>
          <span id="typing-project"></span>
          <span class="w-2 h-4 bg-accent ml-2 animate-pulse"></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROJECTS -->
<section id="projects" class="max-w-5xl mx-auto px-6 py-20 border-b border-border">
  <div class="flex items-center gap-3 mb-10">
    <span class="font-mono text-accent text-sm">01.</span>
    <h2 class="text-2xl font-semibold">Featured Projects</h2>
  </div>

  <?php if (mysqli_num_rows($project) > 0) { ?>
    <div class="grid md:grid-cols-3 gap-4">

      <?php while ($p = mysqli_fetch_assoc($project)) { ?>
        <div class="bg-surface border border-border rounded-lg p-5 flex flex-col gap-4 hover:border-muted transition-colors">
          <div>
            <div class="flex items-start justify-between mb-2">
              <h3 class="font-semibold text-base"><?= htmlspecialchars($p['title']); ?></h3>
              <span class="text-accent font-mono text-xs">↗</span>
            </div>
            <p class="text-dim text-sm leading-relaxed">
              <?php
              $text = $p['description'];
              $firstDot = strpos($text, '.');
              echo htmlspecialchars($firstDot !== false ? substr($text, 0, $firstDot + 1) : $text);
              ?>
            </p>
          </div>
          <div class="flex flex-wrap gap-2 mt-auto">
            <?php
            $tech = mysqli_query($conn, "SELECT tech_stacks.name FROM project_tech JOIN tech_stacks ON project_tech.tech_id = tech_stacks.id WHERE project_tech.project_id = {$p['id']}");
            while ($t = mysqli_fetch_assoc($tech)) { ?>
              <span class="tag"><?= htmlspecialchars($t['name']); ?></span>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    </div>

  <?php } else { ?>

    <div class="flex flex-col items-center justify-center py-16 text-center">
      <span class="text-4xl mb-4">🚧</span>
      <p class="text-dim text-sm">No projects yet. Check back soon!</p>
    </div>

  <?php } ?>

</section>

<!-- CODE SNIPPETS -->
<section id="snippets" class="max-w-5xl mx-auto px-6 py-20 border-b border-border">
  <div class="flex items-center gap-3 mb-10">
    <span class="font-mono text-accent text-sm">02.</span>
    <h2 class="text-2xl font-semibold">Code Snippets</h2>
  </div>

  <?php if (mysqli_num_rows($snippets) > 0) { ?>

    <div class="flex flex-col gap-4">
      <?php while ($s = mysqli_fetch_assoc($snippets)) { ?>

        <div class="bg-surface border border-border rounded-lg overflow-hidden">
          <div class="flex items-center justify-between px-5 py-3 border-b border-border">
            <div class="flex items-center gap-3">
              <span class="font-semibold text-sm"><?= htmlspecialchars($s['title']); ?></span>
              <?php
              $snippets_id = (int) $s['id'];
              $query = "SELECT tech_stacks.name FROM snippet_tech JOIN tech_stacks ON snippet_tech.tech_id = tech_stacks.id WHERE snippet_tech.snippet_id = $snippets_id";
              $tech_snippets = mysqli_query($conn, $query);

              while ($t = mysqli_fetch_assoc($tech_snippets)) { ?>
                <span class="tag tag-accent"><?= htmlspecialchars($t['name']); ?></span>
              <?php } ?>
            </div>
            <button
              class="copy-btn font-mono text-xs text-dim border border-border px-3 py-1 rounded hover:text-text hover:border-muted transition-colors"
              data-target="snippet-<?= $s['id']; ?>">copy</button>
          </div>
          <pre class="text-xs text-dim p-5 overflow-x-auto leading-relaxed"><code id="snippet-<?= $s['id']; ?>"><?= htmlspecialchars($s['code']); ?></code></pre>
          <p class="text-dim text-xs px-5 pb-4"><?= htmlspecialchars($s['explanation']); ?></p>
        </div>

      <?php } ?>
    </div>

  <?php } else { ?>

    <div class="flex flex-col items-center justify-center py-16 text-center">
      <span class="text-4xl mb-4">📭</span>
      <p class="text-dim text-sm">No snippets available yet. Coming soon!</p>
    </div>

  <?php } ?>

</section>

<!-- TECH STACK -->
<section id="stack" class="max-w-5xl mx-auto px-6 py-20 border-b border-border">
  <div class="flex items-center gap-3 mb-10">
    <span class="font-mono text-accent text-sm">03.</span>
    <h2 class="text-2xl font-semibold">Tech Stack</h2>
  </div>
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
    <?php while ($t = mysqli_fetch_assoc($tech_stack)) : ?>
      <div class="bg-surface border border-border rounded-lg p-4 flex flex-col items-center gap-3 hover:border-muted transition-colors h-full">
        <div class="h-12 flex items-center justify-center mb-1">
          <img class="max-w-full max-h-full object-contain" src="assets/img/icon/<?= $t['icon']; ?>" alt="<?= trim($t['name']); ?> ">
        </div>
        <span class="font-mono text-sm font-semibold"><?= $t['name']; ?> </span>
        <span class="text-dim text-xs"><?= $t['type']; ?></span>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<script>
  function typeWriter(elementId, text, speed, delay = 0) {
    setTimeout(() => {
      let i = 0;
      let element = document.getElementById(elementId);

      function typing() {
        if (i < text.length) {
          element.innerHTML += text.charAt(i);
          i++;
          setTimeout(typing, speed);
        }
      }
      typing();
    }, delay);
  }

  // Jalankan animasi saat halaman selesai dimuat
  window.onload = function() {
    // Animasi Nama (Mulai langsung)
    typeWriter('typing-effect', '<?= $admin["nama_lengkap"]; ?>', 200);

    // Animasi Project (Mulai setelah delay 2 detik agar tidak tabrakan)
    typeWriter('typing-project', 'Developing "<?= $new_project["title"] ?? "New Portfolio"; ?>"', 400, 2000);
  };
</script>


<script>
  document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const codeEl = document.getElementById(btn.dataset.target);
      if (!codeEl) return;

      const text = codeEl.innerText;

      // fallback universal
      const textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);

      const original = btn.textContent;
      btn.textContent = 'Copied!';
      btn.classList.add('text-accent', 'border-accent');

      setTimeout(() => {
        btn.textContent = original;
        btn.classList.remove('text-accent', 'border-accent');
      }, 1500);
    });
  });
</script>

<script>
  hljs.highlightAll();
</script>

<?php include_once 'includes/footer.php' ?>