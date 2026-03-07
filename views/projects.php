<?php
include_once '../includes/header.php';

$projects = [];

// ambil semua project
$query = "  SELECT p.id, p.title, p.description, p.image, p.demo_url, p.github_url, GROUP_CONCAT(t.name SEPARATOR ',') AS techs
            FROM projects p
            LEFT JOIN project_tech pt ON p.id = pt.project_id
            LEFT JOIN tech_stacks t ON pt.tech_id = t.id
            GROUP BY p.id
            ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
}

?>

<section class="max-w-5xl mx-auto px-6 pt-20 pb-12 border-b border-border">
    <p class="font-mono text-accent text-sm mb-4 tracking-widest uppercase">Portfolio —</p>
    <h1 class="text-4xl font-semibold tracking-tight leading-none mb-4">All Projects</h1>
    <p class="text-dim max-w-xl leading-relaxed text-base">
        A collection of things I've built using PHP, MySQL, and modern web tools.
    </p>
</section>

<!-- ── Projects Grid ── -->
<section class="max-w-5xl mx-auto px-6 py-16 border-b border-border">

    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-3">
            <span class="font-mono text-accent text-sm">01.</span>
            <h2 class="text-2xl font-semibold">Projects</h2>
        </div>
        <span class="font-mono text-xs text-dim border border-border px-3 py-1 rounded">
            <?= count($projects ); ?> projects
        </span>
    </div>

    <?php if (!empty($projects)) : ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

            <?php foreach ($projects as $p) :
                $techs = is_array($p['techs'])
                    ? $p['techs']
                    : explode(',', $p['techs'] ?? '');
                $hasImage = !empty($p['image']);
                // Generate a deterministic gradient seed from title
                $seed = abs(crc32($p['title']));
                $hue1 = $seed % 360;
                $hue2 = ($hue1 + 40 + ($seed % 60)) % 360;
                $angle = ($seed % 4) * 45 + 135;
                $gradientStyle = "background: linear-gradient({$angle}deg, hsl({$hue1},18%,14%) 0%, hsl({$hue2},22%,18%) 100%);";
            ?>

                <a href="<?= htmlspecialchars($p['url'] ?? '#'); ?>"
                    class="project-card bg-surface border border-border rounded-lg overflow-hidden flex flex-row no-underline group">

                    <!-- ── Right: Image or Auto-gradient ── -->
                    <div class="project-visual w-28 shrink-0 relative overflow-hidden border-l border-border order-2">

                        <?php if ($hasImage) : ?>
                            <img
                                src="<?= htmlspecialchars($p['image']); ?>"
                                alt="<?= htmlspecialchars($p['title']); ?>"
                                class="w-full h-full object-cover transition-transform duration-500 project-img">
                        <?php else : ?>
                            <!-- Auto gradient based on project title -->
                            <div class="w-full h-full flex items-center justify-center relative" style="<?= $gradientStyle ?>">
                                <!-- Noise texture overlay -->
                                <div class="absolute inset-0 noise-overlay"></div>
                                <!-- Diagonal lines pattern -->
                                <div class="absolute inset-0 lines-pattern"></div>
                                <!-- Initials -->
                                <span class="font-mono font-bold text-lg z-10 select-none initials-text"
                                    style="color: hsl(<?= $hue1 ?>, 60%, 65%); text-shadow: 0 0 20px hsl(<?= $hue1 ?>, 60%, 45%);">
                                    <?= htmlspecialchars(strtoupper(substr(trim($p['title']), 0, 2))); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- ── Left: Content ── -->
                    <div class="p-5 flex flex-col gap-3 flex-1 order-1 min-w-0">

                        <!-- Title + arrow -->
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-semibold text-base text-text leading-snug">
                                <?= htmlspecialchars($p['title']); ?>
                            </h3>
                            <span class="arrow-icon font-mono text-xs mt-0.5 shrink-0 transition-transform duration-200">↗</span>
                        </div>

                        <!-- Description -->
                        <p class="text-dim text-sm leading-relaxed flex-1">
                            <?php
                            $desc     = htmlspecialchars($p['description']);
                            $firstDot = strpos($desc, '.');
                            echo $firstDot !== false
                                ? substr($desc, 0, $firstDot + 1)
                                : $desc;
                            ?>
                        </p>

                        <!-- Tech tags -->
                        <?php if (!empty($techs)) : ?>
                            <div class="flex flex-wrap gap-2 mt-auto pt-1">
                                <?php foreach ($techs as $tech) : ?>
                                    <span class="tag"><?= htmlspecialchars(trim($tech)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

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

<?php include_once '../includes/footer.php'; ?>