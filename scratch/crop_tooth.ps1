Add-Type -AssemblyName System.Drawing
$bmp = [System.Drawing.Bitmap]::FromFile('e:\Production-Trial\Dental-Clinic-trial2\assets\images\founder-section-reference.png')
$rect = New-Object System.Drawing.Rectangle(0, 360, 150, 134)
$tooth = $bmp.Clone($rect, $bmp.PixelFormat)
$tooth.Save('e:\Production-Trial\Dental-Clinic-trial2\scratch\tooth-watermark.png', [System.Drawing.Imaging.ImageFormat]::Png)
$tooth.Dispose()
$bmp.Dispose()
Write-Output "Done cropping tooth watermark"
