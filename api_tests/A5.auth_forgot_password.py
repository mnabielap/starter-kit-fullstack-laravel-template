import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

url = f"{utils.BASE_URL}/auth/forgot-password"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

payload = {
    "email": "admin@example.com"
}

utils.send_and_print(
    url,
    method="POST",
    body=payload,
    output_file=output_file
)