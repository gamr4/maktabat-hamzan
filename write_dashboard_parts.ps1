
$part3 = Get-Content "C:\Users\Asus Rog\github\maktabat-hamzan\scripts_section.txt" -Raw
$current = Get-Content "C:\Users\Asus Rog\github\maktabat-hamzan\resources\views\dashboard.blade.php" -Raw
Set-Content -Path "C:\Users\Asus Rog\github\maktabat-hamzan\resources\views\dashboard.blade.php" -Value ($current + "`n" + $part3) -Encoding UTF8
Write-Host "Part 3 done"

