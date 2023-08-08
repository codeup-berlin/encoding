from django.core.signing import b62_encode, b62_decode

for v in [
"", "0", "1", "-1", "0.1", "bla",
"9223372036854775807", "1179c90c-e3d2-4ce7-8870-3df743add313", "00000000-0000-0000-0000-000000000000", "0000815"
]:
    print("'{}' => '{}'".format(v, b62_encode(int.from_bytes(v.encode()))))

for v in [0, 1, -1, 10, 32432, 9223372036854775807, -9223372036854775808]:
    print("{} => '{}'".format(v, b62_encode(v)))
