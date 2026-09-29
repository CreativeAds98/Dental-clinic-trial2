Add-Type -AssemblyName System.Drawing

$srcPath = "C:\Users\lenovo\.gemini\antigravity-ide\brain\98cdd07d-45de-4ec0-8353-5ef033338111\.user_uploaded\media_1790616835278.jpg"
$bmp = [System.Drawing.Bitmap]::FromFile($srcPath)
Write-Output "Original Size: $($bmp.Width) x $($bmp.Height)"

# Crop founder visual portion (right side and background)
# Navbar ends around y=85, section ends around y=576
$top = 82
$height = $bmp.Height - $top
$rect = New-Object System.Drawing.Rectangle(0, $top, $bmp.Width, $height)
$sectionBmp = $bmp.Clone($rect, $bmp.PixelFormat)
$sectionBmp.Save("e:\Production-Trial\Dental-Clinic-trial2\assets\images\founder-section-reference.png", [System.Drawing.Imaging.ImageFormat]::Png)
$sectionBmp.Dispose()

# Now crop the doctor organic visual for the right column
# Doctor visual starts around x=370 to end
$cropX = 360
$cropWidth = $bmp.Width - $cropX
$rectRight = New-Object System.Drawing.Rectangle($cropX, $top, $cropWidth, $height)
$rightBmp = $bmp.Clone($rectRight, $bmp.PixelFormat)
$rightBmp.Save("e:\Production-Trial\Dental-Clinic-trial2\assets\images\founder-doctor-visual.png", [System.Drawing.Imaging.ImageFormat]::Png)
$rightBmp.Dispose()

$bmp.Dispose()
Write-Output "Done cropping reference and doctor visual!"
