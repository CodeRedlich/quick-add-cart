<?php declare(strict_types=1);
$path = $argv[1] ?? __DIR__ . '/../build/coverage/clover.xml';
if (!is_file($path)) {
    fwrite(STDERR, "Coverage report missing: $path\n");
    exit(1);
}
$report = simplexml_load_file($path);
$metrics = $report === false ? null : $report->project->metrics;
if ($metrics === null || (int) $metrics['statements'] === 0) {
    fwrite(STDERR, "Coverage report contains no executable statements.\n");
    exit(1);
}
$total = (int) $metrics['statements'];
$covered = (int) $metrics['coveredstatements'];
printf("PHP line coverage: %d/%d (%.2f%%)\n", $covered, $total, 100 * $covered / $total);
exit($total === $covered ? 0 : 1);

