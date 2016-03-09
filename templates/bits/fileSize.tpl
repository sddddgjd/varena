{* Writes a file size in human-readable format *}
{$units = ['B', 'kB', 'MB']}
{$i = 0}
{$format = "%d"}
{while ($s >= 1000) && ($i + 1 < count($units))}
  {$s = $s / 1000}
  {$i = $i + 1}
  {$format = "%0.1lf"}
{/while}

{$s|string_format:$format} {$units[$i]}
