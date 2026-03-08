<?php
include_once '../includes/header.php';

// ── Admin check ──────────────────────────────────────────────
// Ganti baris ini dengan session check milikmu, contoh:
// $is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

$projects = [];

$query = "SELECT p.id, p.title, p.description, p.image, p.demo_url, p.github_url,
                 p.views, p.created_at,
                 GROUP_CONCAT(t.name SEPARATOR ',') AS techs
          FROM projects p
          LEFT JOIN project_tech pt ON p.id = pt.project_id
          LEFT JOIN tech_stacks t   ON pt.tech_id = t.id
          GROUP BY p.id
          ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
}

// Semua tech stack untuk dropdown modal
$all_techs   = [];
$tech_result = mysqli_query($conn, "SELECT id, name FROM tech_stacks ORDER BY name ASC");
while ($t = mysqli_fetch_assoc($tech_result)) {
    $all_techs[] = $t;
}
?>

<!-- ── Page Header ── -->
<section class="max-w-5xl mx-auto px-6 pt-20 pb-12 border-b border-border">
    <p class="font-mono text-accent text-sm mb-4 tracking-widest uppercase">Portfolio —</p>
    <h1 class="text-4xl font-semibold tracking-tight leading-none mb-4">All Projects</h1>
    <p class="text-dim max-w-xl leading-relaxed text-base">
        A collection of things I've built using PHP, MySQL, and modern web tools.
    </p>
</section>

<!-- ── Projects Grid ── -->
<section class="max-w-5xl mx-auto px-6 py-16 border-b border-border">

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">

        <div class="flex items-center gap-3">
            <span class="font-mono text-accent text-sm">01.</span>
            <h2 class="text-2xl font-semibold">Projects</h2>
            <span id="project-count"
                class="font-mono text-xs text-dim border border-border px-3 py-1 rounded">
                <?= count($projects); ?> projects
            </span>
        </div>

        <div class="flex items-center gap-3">

            <!-- Search input -->
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input id="search-input" type="text" placeholder="Search projects..."
                    class="bg-surface border border-border rounded font-mono text-xs text-text
                              placeholder-muted pl-8 pr-4 py-2 w-48 focus:outline-none
                              focus:border-muted transition-colors" />
            </div>

            <?php if ($is_admin) : ?>
                <!-- Add Project — admin only -->
                <button onclick="openModal()"
                    class="flex items-center gap-1.5 bg-accent text-bg font-mono text-xs
                           font-semibold px-4 py-2 rounded hover:opacity-90 transition-opacity whitespace-nowrap">
                    <span class="text-base leading-none">+</span> Add Project
                </button>
            <?php endif; ?>

        </div>
    </div>

    <!-- Empty search message -->
    <div id="no-results" class="hidden flex-col items-center justify-center py-16 text-center">
        <span class="font-mono text-accent text-3xl mb-3">◌</span>
        <p class="text-dim text-sm">No projects match your search.</p>
    </div>

    <?php if (!empty($projects)) : ?>

        <div id="projects-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

            <?php foreach ($projects as $p) :
                $techs    = is_array($p['techs'])
                    ? $p['techs']
                    : explode(',', $p['techs'] ?? '');
                $hasImage = !empty($p['image']);
                $seed     = abs(crc32($p['title']));
                $hue1     = $seed % 360;
                $hue2     = ($hue1 + 40 + ($seed % 60)) % 360;
                $angle    = ($seed % 4) * 45 + 135;
                $gradStyle = "background:linear-gradient({$angle}deg,hsl({$hue1},18%,14%) 0%,hsl({$hue2},22%,18%) 100%);";
                $techStr  = implode(' ', array_map('strtolower', array_map('trim', $techs)));
            ?>

                <a href="<?= htmlspecialchars($p['demo_url'] ?? '#'); ?>"
                    data-title="<?= strtolower(htmlspecialchars($p['title'])); ?>"
                    data-techs="<?= htmlspecialchars($techStr); ?>"
                    class="project-card bg-surface border border-border rounded-lg overflow-hidden flex flex-row no-underline group">

                    <!-- Right: visual -->
                    <div class="w-28 shrink-0 relative overflow-hidden border-l border-border order-2">
                        <?php if ($hasImage) : ?>
                            <img src="<?= htmlspecialchars($p['image']); ?>"
                                alt="<?= htmlspecialchars($p['title']); ?>"
                                class="w-full h-full object-cover">
                        <?php else : ?>
                            <div class="w-full h-full flex items-center justify-center relative" style="<?= $gradStyle ?>">
                                <div class="absolute inset-0 opacity-10"
                                    style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:8px 8px;"></div>
                                <span class="font-mono font-bold text-lg z-10 select-none"
                                    style="color:hsl(<?= $hue1 ?>,60%,65%);text-shadow:0 0 20px hsl(<?= $hue1 ?>,60%,45%);">
                                    <?= htmlspecialchars(strtoupper(substr(trim($p['title']), 0, 2))); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Left: content -->
                    <div class="p-5 flex flex-col gap-3 flex-1 order-1 min-w-0">

                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-semibold text-base text-text leading-snug">
                                <?= htmlspecialchars($p['title']); ?>
                            </h3>
                            <span class="font-mono text-muted text-xs mt-0.5 shrink-0 group-hover:text-accent transition-colors">↗</span>
                        </div>

                        <p class="text-dim text-sm leading-relaxed flex-1">
                            <?php
                            $desc     = htmlspecialchars($p['description']);
                            $firstDot = strpos($desc, '.');
                            echo $firstDot !== false ? substr($desc, 0, $firstDot + 1) : $desc;
                            ?>
                        </p>

                        <!-- Tags + view count row -->
                        <div class="flex items-end justify-between gap-2 mt-auto pt-1">

                            <?php if (!empty($techs)) : ?>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($techs as $tech) : ?>
                                        <span class="tag"><?= htmlspecialchars(trim($tech)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($is_admin) : ?>
                                <!-- View count — admin only -->
                                <div class="flex items-center gap-1 shrink-0 ml-2" title="Total views">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <span class="font-mono text-muted" style="font-size:0.65rem;">
                                        <?= number_format((int)($p['views'] ?? 0)); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    <?php else : ?>
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <span class="font-mono text-accent text-4xl mb-4">🚧</span>
            <p class="text-dim text-sm mb-1">No projects yet.</p>
            <p class="text-muted text-xs font-mono">Check back soon!</p>
        </div>
    <?php endif; ?>

</section>


<!-- ════════════════════════════════════════════
     MODAL — only rendered for admin
     ════════════════════════════════════════════ -->
<?php if ($is_admin) : ?>

    <!-- Backdrop -->
    <div id="modal-backdrop"
        onclick="closeModal()"
        class="fixed inset-0 z-40 hidden"
        style="background:rgba(0,0,0,0.78);backdrop-filter:blur(4px);">
    </div>

    <!-- Modal panel -->
    <div id="add-modal"
        class="fixed z-50 hidden"
        style="top:50%;left:50%;transform:translate(-50%,-50%);
            width:min(96vw,560px);max-height:90vh;overflow-y:auto;">

        <div class="bg-surface border border-border rounded-xl p-7 flex flex-col gap-5">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="font-mono text-accent">+</span>
                    <h3 class="text-lg font-semibold">Add New Project</h3>
                </div>
                <button onclick="closeModal()"
                    class="font-mono text-dim hover:text-text text-xl leading-none transition-colors w-7 h-7 flex items-center justify-center">
                    ✕
                </button>
            </div>

            <!-- Divider -->
            <div class="border-t border-border"></div>

            <!-- Form -->
            <form action="add_project.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">

                <!-- Title -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-mono text-xs text-dim uppercase tracking-wider">Title *</label>
                    <input type="text" name="title" required placeholder="e.g. TaskFlow"
                        class="bg-bg border border-border rounded px-4 py-2.5 text-sm text-text
                              placeholder-muted font-sans focus:outline-none focus:border-muted transition-colors">
                </div>

                <!-- Description -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-mono text-xs text-dim uppercase tracking-wider">Description *</label>
                    <textarea name="description" required rows="3"
                        placeholder="Short description of the project..."
                        class="bg-bg border border-border rounded px-4 py-2.5 text-sm text-text
                                 placeholder-muted font-sans focus:outline-none focus:border-muted
                                 transition-colors resize-none"></textarea>
                </div>

                <!-- Image upload -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-mono text-xs text-dim uppercase tracking-wider">Cover Image</label>
                    <label class="flex items-center gap-3 bg-bg border border-border border-dashed rounded
                              px-4 py-3 cursor-pointer hover:border-muted transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="text-muted group-hover:text-dim transition-colors shrink-0">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <span class="font-mono text-xs text-muted group-hover:text-dim transition-colors" id="file-label">
                            Choose image (JPG, PNG, WEBP)
                        </span>
                        <input type="file" name="image" accept="image/*" class="hidden"
                            onchange="updateFileLabel(this)">
                    </label>
                </div>

                <!-- Demo + GitHub URLs -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="font-mono text-xs text-dim uppercase tracking-wider">Demo URL</label>
                        <input type="url" name="demo_url" placeholder="https://..."
                            class="bg-bg border border-border rounded px-4 py-2.5 text-sm text-text
                                  placeholder-muted font-sans focus:outline-none focus:border-muted transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="font-mono text-xs text-dim uppercase tracking-wider">GitHub URL</label>
                        <input type="url" name="github_url" placeholder="https://github.com/..."
                            class="bg-bg border border-border rounded px-4 py-2.5 text-sm text-text
                                  placeholder-muted font-sans focus:outline-none focus:border-muted transition-colors">
                    </div>
                </div>

                <!-- Tech Stack checkboxes -->
                <div class="flex flex-col gap-2">
                    <label class="font-mono text-xs text-dim uppercase tracking-wider">Tech Stack</label>
                    <div class="bg-bg border border-border rounded p-4 grid grid-cols-2 gap-y-2 gap-x-4"
                        style="max-height:140px;overflow-y:auto;">
                        <?php foreach ($all_techs as $tech) : ?>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="tech_ids[]" value="<?= $tech['id']; ?>"
                                    class="w-3.5 h-3.5 shrink-0 accent-[#e8ff47]">
                                <span class="font-mono text-xs text-dim group-hover:text-text transition-colors">
                                    <?= htmlspecialchars($tech['name']); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-border">
                    <button type="button" onclick="closeModal()"
                        class="font-mono text-xs text-dim border border-border px-4 py-2 rounded
                               hover:border-muted hover:text-text transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-accent text-bg font-mono text-xs font-semibold px-5 py-2 rounded
                               hover:opacity-90 transition-opacity">
                        Save Project
                    </button>
                </div>

            </form>
        </div>
    </div>

<?php endif; ?>


<!-- ── Styles ── -->
<style>
    .tag {
        display: inline-block;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        padding: 2px 8px;
        border: 1px solid #2a2a2a;
        border-radius: 4px;
        color: #888;
        white-space: nowrap;
    }

    .project-card {
        transition: border-color .2s ease, background-color .2s ease;
        cursor: pointer;
    }

    .project-card:hover {
        border-color: #555;
        background-color: #1f1f1f;
    }

    /* Custom scrollbars */
    #add-modal,
    #add-modal .grid {
        scrollbar-width: thin;
        scrollbar-color: #2a2a2a #0f0f0f;
    }

    #add-modal::-webkit-scrollbar,
    #add-modal .grid::-webkit-scrollbar {
        width: 4px;
    }

    #add-modal::-webkit-scrollbar-thumb,
    #add-modal .grid::-webkit-scrollbar-thumb {
        background: #2a2a2a;
        border-radius: 2px;
    }
</style>

<!-- ── Scripts ── -->
<script>
    /* ── Modal ── */
    function openModal() {
        document.getElementById('modal-backdrop').classList.remove('hidden');
        document.getElementById('add-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modal-backdrop').classList.add('hidden');
        document.getElementById('add-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });

    /* ── File label ── */
    function updateFileLabel(input) {
        document.getElementById('file-label').textContent =
            input.files[0]?.name ?? 'Choose image (JPG, PNG, WEBP)';
    }

    /* ── Live search ── */
    const searchInput = document.getElementById('search-input');
    const cards = document.querySelectorAll('.project-card');
    const noResults = document.getElementById('no-results');
    const countBadge = document.getElementById('project-count');
    const totalAll = cards.length;

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.toLowerCase().trim();
        let visible = 0;

        cards.forEach(card => {
            const match = !q ||
                (card.dataset.title ?? '').includes(q) ||
                (card.dataset.techs ?? '').includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        noResults.classList.toggle('hidden', visible > 0);
        noResults.classList.toggle('flex', visible === 0);
        countBadge.textContent = (q ? `${visible} / ${totalAll}` : `${visible}`) + ' projects';
    });
</script>

<?php include_once '../includes/footer.php'; ?>