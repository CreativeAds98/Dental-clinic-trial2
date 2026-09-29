Add-Type -AssemblyName System.Drawing
$bmp = [System.Drawing.Bitmap]::FromFile('e:\Production-Trial\Dental-Clinic-trial2\assets\images\founder-section-reference.png')
$cropX = 465
$cropW = $bmp.Width - $cropX
$cropH = $bmp.Height
$rect = New-Object System.Drawing.Rectangle($cropX, 0, $cropW, $cropH)
$cropped = $bmp.Clone($rect, $bmp.PixelFormat)
$cropped.Save('e:\Production-Trial\Dental-Clinic-trial2\assets\images\founder-doctor-visual.png', [System.Drawing.Imaging.ImageFormat]::Png)
$cropped.Dispose()
$bmp.Dispose()
Write-Output "Cropped cleanly from x=$cropX to $($cropX + $cropW) ($cropW x $cropH)"
