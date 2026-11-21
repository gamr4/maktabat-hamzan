\ = Get-Content 'C:\Users\Asus Rog\github\maktabat-hamzan\write_base64.ps1' -Raw
# Extract the base64 content between the quotes
\ = \ -replace \"^.*?'(.*)'.*$\", '\'
\ = [System.Convert]::FromBase64String(\)
\ = [System.Text.Encoding]::UTF8.GetString(\)
[System.IO.File]::WriteAllText('C:\Users\Asus Rog\github\maktabat-hamzan\resources\views\dashboard.blade.php', \, [System.Text.Encoding]::UTF8)
Write-Host 'dashboard.blade.php written successfully'
